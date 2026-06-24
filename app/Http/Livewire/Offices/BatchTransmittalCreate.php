<?php

namespace App\Http\Livewire\Offices;

use App\Models\BatchTransmittal;
use App\Models\DisbursementVoucher;
use App\Services\DisbursementVouchers\DisbursementVoucherWorkflowService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class BatchTransmittalCreate extends Component
{
    public $selectedDvs = [];
    public $dvRemarks = [];
    public $scanInput = '';
    public $officeGroupId;
    public $officeName;
    public $destination = '';
    public $availableDestinations = [];

    public function mount()
    {
        $office = auth()->user()->employee_information?->office;
        if (!in_array($office?->office_group_id, [1, 2, 3, 4, 5])) {
            abort(403);
        }

        $this->officeGroupId = $office->office_group_id;
        $this->officeName = $office->name;

        $this->loadAvailableDestinations();

        // Auto-select if only one destination
        if (count($this->availableDestinations) === 1) {
            $this->destination = array_key_first($this->availableDestinations);
        }
    }

    public function loadAvailableDestinations()
    {
        $workflowService = app(DisbursementVoucherWorkflowService::class);

        $dvs = DisbursementVoucher::with(['current_step', 'voucher_subtype.related_documents_list'])
            ->whereRelation('current_step', 'office_group_id', $this->officeGroupId)
            ->where('for_cancellation', false)
            ->whereNull('pending_return_step_id')
            ->get()
            ->filter(fn ($dv) => $workflowService->canBeForwarded($dv));

        $destinations = [];
        foreach ($dvs as $dv) {
            $nextStep = $dv->current_step->nextStep;
            if ($nextStep) {
                $key = $nextStep->recipient;
                if (!isset($destinations[$key])) {
                    $destinations[$key] = $key;
                }
            }
        }

        $this->availableDestinations = $destinations;
    }

    public function getForwardableDvsProperty()
    {
        $workflowService = app(DisbursementVoucherWorkflowService::class);

        $query = DisbursementVoucher::with(['current_step', 'disbursement_voucher_particulars', 'voucher_subtype.related_documents_list'])
            ->whereRelation('current_step', 'office_group_id', $this->officeGroupId)
            ->where('for_cancellation', false)
            ->whereNull('pending_return_step_id')
            ->latest('submitted_at')
            ->get()
            ->filter(function ($dv) use ($workflowService) {
                if (!$workflowService->canBeForwarded($dv)) {
                    return false;
                }
                // Filter by selected destination
                if ($this->destination) {
                    $nextStep = $dv->current_step->nextStep;
                    return $nextStep && $nextStep->recipient === $this->destination;
                }
                return true;
            })
            ->values();

        return $query;
    }

    public function updatedDestination()
    {
        $this->selectedDvs = [];
        $this->dvRemarks = [];
    }

    public function toggleDv($dvId)
    {
        if (in_array($dvId, $this->selectedDvs)) {
            $this->selectedDvs = array_values(array_diff($this->selectedDvs, [$dvId]));
        } else {
            $this->selectedDvs[] = $dvId;
        }
    }

    public function selectAll()
    {
        $this->selectedDvs = $this->forwardableDvs->pluck('id')->toArray();
    }

    public function deselectAll()
    {
        $this->selectedDvs = [];
    }

    public function updatedScanInput($value)
    {
        if (empty($value)) return;

        // QR codes contain a URL — extract the tracking number from it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $route = Route::getRoutes()->match(Request::create($value));
            if ($route->getName() == 'disbursement-vouchers.show-from-trn') {
                $value = $route->parameters['disbursement_voucher'];
            }
        }

        $dv = DisbursementVoucher::with(['current_step', 'voucher_subtype.related_documents_list'])
            ->where('tracking_number', $value)
            ->whereRelation('current_step', 'office_group_id', $this->officeGroupId)
            ->first();

        if ($dv && !in_array($dv->id, $this->selectedDvs)) {
            $workflowService = app(DisbursementVoucherWorkflowService::class);
            if ($workflowService->canBeForwarded($dv)) {
                // Auto-set destination if not selected yet
                if (!$this->destination) {
                    $nextStep = $dv->current_step->nextStep;
                    if ($nextStep) {
                        $this->destination = $nextStep->recipient;
                    }
                }
                // Check if DV matches selected destination
                $nextStep = $dv->current_step->nextStep;
                if ($nextStep && $nextStep->recipient === $this->destination) {
                    $this->selectedDvs[] = $dv->id;
                    Notification::make()->title("Added: {$dv->tracking_number}")->success()->send();
                } else {
                    Notification::make()->title("DV destination ({$nextStep->recipient}) does not match selected destination ({$this->destination}).")->warning()->send();
                }
            } else {
                Notification::make()->title('DV cannot be forwarded yet.')->warning()->send();
            }
        } elseif ($dv && in_array($dv->id, $this->selectedDvs)) {
            Notification::make()->title('DV already in batch.')->warning()->send();
        } else {
            Notification::make()->title('DV not found in this office.')->warning()->send();
        }

        $this->scanInput = '';
    }

    public function createAndForward()
    {
        if (empty($this->selectedDvs)) {
            Notification::make()->title('Please select at least one DV.')->warning()->send();
            return;
        }

        if (empty($this->destination)) {
            Notification::make()->title('Please select a destination.')->warning()->send();
            return;
        }

        $workflowService = app(DisbursementVoucherWorkflowService::class);
        $dvs = DisbursementVoucher::with(['current_step', 'voucher_subtype.related_documents_list'])
            ->whereIn('id', $this->selectedDvs)
            ->get();

        DB::transaction(function () use ($dvs, $workflowService) {
            $serial = BatchTransmittal::generateSerialNumber($this->officeGroupId);

            $batch = BatchTransmittal::create([
                'office_group_id' => $this->officeGroupId,
                'serial_number' => $serial,
                'from_office_name' => $this->officeName,
                'to_office_name' => $this->destination,
                'created_by' => auth()->id(),
                'forwarded_by' => auth()->id(),
                'forwarded_at' => now(),
            ]);

            foreach ($dvs as $dv) {
                $batch->items()->create([
                    'disbursement_voucher_id' => $dv->id,
                    'remarks' => $this->dvRemarks[$dv->id] ?? null,
                ]);

                if ($workflowService->canBeForwarded($dv)) {
                    $workflowService->forward($dv, auth()->user(), null, [
                        'batch_transmittal_number' => $serial,
                    ]);
                }
            }

            Notification::make()
                ->title("Batch Transmittal No. {$serial} created and forwarded with {$dvs->count()} DV(s).")
                ->success()
                ->send();

            $this->redirect(route('office.batch-transmittal.print', $batch));
        });
    }

    public function render()
    {
        return view('livewire.offices.batch-transmittal-create');
    }
}
