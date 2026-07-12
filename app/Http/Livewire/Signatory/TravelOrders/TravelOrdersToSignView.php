<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Http\Controllers\NotificationController;
use App\Jobs\SendSmsJob;
use App\Models\Itinerary;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use App\Models\User;
use DB;
use Filament\Notifications\Notification;
use Livewire\Component;
use WireUi\Traits\Actions;

class TravelOrdersToSignView extends Component
{
    use Actions;

    public $rejectionNote;

    public TravelOrder $travel_order;

    public $modal = false;

    public $modalRejection = false;

    public $modalItineraryReturn = false;

    public $modalTravelOrderReturn = false;

    public $itineraryReturnNote = '';

    public $travelOrderReturnNote = '';

    public $returningItineraryId;

    public $limit = 3;

    public $note = '';

    public $from_oic = false;

    public $oic = false;

    public $oic_signatory;

    public function mount()
    {
        $this->travel_order->load(['applicants.employee_information', 'request_schedule']);
        if (request('from_oic')) {
            $this->from_oic = (bool) request('from_oic');
            $this->oic_signatory = (int) request('oic_signatory');
        }
    }

    public function toggleTravelOrderType()
    {

        $this->travel_order->refresh();
        $officerName = $this->from_oic
            ? User::find($this->oic_signatory)?->employee_information->full_name
            : auth()->user()->employee_information->full_name;

        if ($this->travel_order->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
            // dd($this->travel_order->travel_order_type_id, TravelOrderType::OFFICIAL_TIME, 'OFFICIAL_TIME');
            $this->travel_order->update([
                'travel_order_type_id' => TravelOrderType::OFFICIAL_TIME,
            ]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order type changed to Official Time.', 'user_id' => auth()->id()]);

            // Send SMS notifications to all applicants
            $applicants = $this->travel_order->applicants()->with('employee_information')->get();
            $message = "Your travel on official business has been converted by {$officerName} to one on official time. No travel allowances shall be granted.";

            // ========== SMS NOTIFICATION ==========
            foreach ($applicants as $applicant) {
                if ($applicant->employee_information && ! empty($applicant->employee_information->contact_number)) {
                    if (! config('services.semaphore.api_key')) {
                        break;
                    }
                    SendSmsJob::dispatch(
                        $applicant->employee_information->contact_number,
                        $message,
                        'travel_order_type_converted',
                        $applicant->id,
                        $this->from_oic ? $this->oic_signatory : auth()->id()
                    );
                }
            }
            // ========== SMS NOTIFICATION END ==========

            // ========== REALTIME NOTIFICATION ==========
            try {
                foreach ($applicants as $applicant) {
                    NotificationController::sendGeneralNotification(
                        'travel_order_type_converted',
                        'Travel Order Type Converted',
                        $message,
                        $applicant,
                        route('requisitioner.travel-orders.show', $this->travel_order),
                        $this->from_oic ? $this->oic_signatory : auth()->id()
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Realtime notification failed: '.$e->getMessage());
            }
            // ========== REALTIME NOTIFICATION END ==========
        } else {
            // dd($this->travel_order->travel_order_type_id, TravelOrderType::OFFICIAL_BUSINESS, 'OFFICIAL_BUSINESS');
            $this->travel_order->update([
                'travel_order_type_id' => TravelOrderType::OFFICIAL_BUSINESS,
            ]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order type changed to Official Business.', 'user_id' => auth()->id()]);

            // Send SMS notifications to all applicants
            $applicants = $this->travel_order->applicants()->with('employee_information')->get();
            $message = "Your travel on official time has been converted by {$officerName} to one on official business. Travel allowances will be granted.";

            // ========== SMS NOTIFICATION ==========
            foreach ($applicants as $applicant) {
                if (! config('services.semaphore.api_key')) {
                    break;
                }
                if ($applicant->employee_information && ! empty($applicant->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        $applicant->employee_information->contact_number,
                        $message,
                        'travel_order_type_converted',
                        $applicant->id,
                        $this->from_oic ? $this->oic_signatory : auth()->id()
                    );
                }
            }
            // ========== SMS NOTIFICATION END ==========

            // ========== REALTIME NOTIFICATION ==========
            try {
                foreach ($applicants as $applicant) {
                    NotificationController::sendGeneralNotification(
                        'travel_order_type_converted',
                        'Travel Order Type Converted',
                        $message,
                        $applicant,
                        route('requisitioner.travel-orders.show', $this->travel_order),
                        $this->from_oic ? $this->oic_signatory : auth()->id()
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Realtime notification failed: '.$e->getMessage());
            }
            // ========== REALTIME NOTIFICATION END ==========
        }

        $this->travel_order->refresh();

        Notification::make()
            ->success()
            ->title('Travel Order Type Changed')
            ->body("Travel Order Type has been changed to {$this->travel_order->travel_order_type->name}.")
            ->send();
    }

    public function render()
    {
        $itineraries = $this->travel_order->itineraries()->with('user.employee_information')->get();

        return view('livewire.signatory.travel-orders.travel-orders-to-sign-view', [
            'sidenotes' => $this->travel_order->sidenotes()->limit($this->limit)->with('user.employee_information')->get(),
            'itineraries' => $itineraries,
            'hasCompleteSubmittedItineraries' => $this->hasCompleteSubmittedItineraries($itineraries),
        ]);
    }

    public function removeApplicant(User $user)
    {
        DB::beginTransaction();
        $this->travel_order->applicants()->updateExistingPivot($user->id, ['deleted_at' => now()]);
        $this->travel_order->sidenotes()->create(['content' => "Removed applicant {$user->employee_information->full_name} from travel order.", 'user_id' => auth()->id()]);
        $this->travel_order->refresh();
        DB::commit();

        // ========== REALTIME NOTIFICATION ==========
        try {
            $officerName = auth()->user()->employee_information->full_name ?? auth()->user()->name;
            NotificationController::sendGeneralNotification(
                'travel_order_applicant_removed',
                'Removed from Travel Order',
                "You have been removed from travel order ref. no. {$this->travel_order->tracking_code} by {$officerName}.",
                $user,
                route('requisitioner.travel-orders.show', $this->travel_order)
            );
        } catch (\Exception $e) {
            \Log::error('Realtime notification failed: '.$e->getMessage());
        }
        // ========== REALTIME NOTIFICATION END ==========

        $this->dialog()->success(
            $title = 'Applicant removed',
            $description = 'The applicant has been removed from the travel order.',
            $icon = 'success',
        );
    }

    public function restoreApplicant(User $user)
    {
        DB::beginTransaction();
        $this->travel_order->removed_applicants()->updateExistingPivot($user->id, ['deleted_at' => null]);
        $this->travel_order->sidenotes()->create(['content' => "Restored applicant {$user->employee_information->full_name} to travel order.", 'user_id' => auth()->id()]);
        $this->travel_order->refresh();
        DB::commit();

        // ========== REALTIME NOTIFICATION ==========
        try {
            $officerName = auth()->user()->employee_information->full_name ?? auth()->user()->name;
            NotificationController::sendGeneralNotification(
                'travel_order_applicant_restored',
                'Re-added to Travel Order',
                "You have been re-added to travel order ref. no. {$this->travel_order->tracking_code} by {$officerName}.",
                $user,
                route('requisitioner.travel-orders.show', $this->travel_order)
            );
        } catch (\Exception $e) {
            \Log::error('Realtime notification failed: '.$e->getMessage());
        }
        // ========== REALTIME NOTIFICATION END ==========

        $this->dialog()->success(
            $title = 'Applicant restored',
            $description = 'The applicant has been restored from the travel order.',
            $icon = 'success',
        );
    }

    public function showDialog() {}

    public function returnItinerary(Itinerary $itinerary)
    {
        abort_unless($itinerary->travel_order_id === $this->travel_order->id, 403);

        $this->returningItineraryId = $itinerary->id;
        $this->itineraryReturnNote = '';
        $this->modalItineraryReturn = true;
    }

    public function confirmReturnItinerary()
    {
        $this->validate(['itineraryReturnNote' => 'required']);

        $itinerary = $this->travel_order->itineraries()
            ->with('user.employee_information')
            ->findOrFail($this->returningItineraryId);

        $itinerary->update([
            'approved_at' => null,
            'submitted_at' => null,
        ]);

        $applicantName = $itinerary->user?->employee_information?->full_name ?? $itinerary->user?->name;
        $this->travel_order->sidenotes()->create([
            'content' => "Itinerary returned for editing ({$applicantName}): {$this->itineraryReturnNote}",
            'user_id' => auth()->id(),
        ]);

        $this->modalItineraryReturn = false;
        $this->returningItineraryId = null;
        $this->itineraryReturnNote = '';
        $this->travel_order->refresh();

        $this->dialog()->success(
            $title = 'Itinerary returned',
            $description = 'The itinerary has been returned for editing.',
            $icon = 'success',
        );
    }

    public function returnTravelOrder()
    {
        $this->travelOrderReturnNote = '';
        $this->modalTravelOrderReturn = true;
    }

    public function confirmReturnTravelOrder()
    {
        $this->validate(['travelOrderReturnNote' => 'required']);

        DB::beginTransaction();

        $this->travel_order->update(['submitted_at' => null]);

        DB::table('travel_order_signatories')
            ->where('travel_order_id', $this->travel_order->id)
            ->update([
                'is_approved' => false,
                'approved_at' => null,
                'approved_by_oic_id' => null,
                'updated_at' => now(),
            ]);

        $this->travel_order->itineraries()->update([
            'approved_at' => null,
            'submitted_at' => null,
        ]);

        $this->travel_order->sidenotes()->create([
            'content' => 'Travel order returned for editing: '.$this->travelOrderReturnNote,
            'user_id' => auth()->id(),
        ]);

        DB::commit();

        $this->modalTravelOrderReturn = false;
        $this->travelOrderReturnNote = '';
        $this->travel_order->refresh();

        $this->dialog()->success(
            $title = 'Travel order returned',
            $description = 'The travel order and itineraries have been returned to draft.',
            $icon = 'success',
        );
    }

    public function showMore()
    {
        $this->limit += 5;
    }

    public function addNote()
    {
        $this->validate(['note' => 'required']);
        $content = '';
        if ($this->from_oic) {
            $content .= "OIC's Note: ";
        }
        $content .= $this->note;
        $this->travel_order->sidenotes()->create(['content' => $content, 'user_id' => auth()->id()]);
        $this->modal = false;
        $this->note = '';
        $this->travel_order->refresh();
        $this->dialog()->success(
            $title = 'Note attached',
            $description = 'Your note has been attached and saved!',
            $icon = 'success',
        );
    }

    public function approve()
    {
        $this->travel_order->refresh();

        if (blank($this->travel_order->submitted_at)) {
            $this->dialog()->error(
                $title = 'Action unavailable',
                $description = 'This travel order is pending resubmission.',
                $icon = 'error',
            );

            return;
        }

        if (! $this->hasCompleteSubmittedItineraries()) {
            $this->dialog()->error(
                $title = 'Action unavailable',
                $description = 'Action on the TO and itineraries cannot be performed because itineraries are not yet complete.',
                $icon = 'error',
            );

            return;
        }

        if ($this->travel_order->needs_vehicle && ! $this->travel_order->request_schedule) {
            $this->dialog()->error(
                $title = 'Action unavailable',
                $description = 'Travel Order requires a vehicle but no vehicle request was created by applicants.',
                $icon = 'error',
            );

            return;
        }

        $approvedAt = now();
        $oicId = $this->from_oic ? auth()->id() : null;

        if ($this->from_oic) {
            $this->travel_order->signatories()->updateExistingPivot($this->oic_signatory, [
                'is_approved' => true,
                'approved_at' => $approvedAt,
                'approved_by_oic_id' => $oicId,
            ]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order approved as OIC for: '.User::find($this->oic_signatory)?->employee_information->full_name, 'user_id' => auth()->id()]);
            $officerName = User::find($this->oic_signatory)?->employee_information->full_name;
        } else {
            $this->travel_order->signatories()->updateExistingPivot(auth()->id(), [
                'is_approved' => true,
                'approved_at' => $approvedAt,
                'approved_by_oic_id' => $oicId,
            ]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order approved.', 'user_id' => auth()->id()]);
            $officerName = auth()->user()->employee_information->full_name;
        }

        // Refresh to get updated signatory approval status
        $this->travel_order->refresh();

        // Check if ALL signatories have approved (only send SMS on final approval)
        $allApproved = $this->travel_order->signatories()
            ->wherePivot('is_approved', 1)
            ->count() === $this->travel_order->signatories()->count();

        // Only send SMS if this is the FINAL approval (all signatories approved)
        if ($allApproved) {
            // Send SMS notifications to all applicants
            $applicants = $this->travel_order->applicants()->with('employee_information')->get();
            $message = "Your travel order with ref. no. {$this->travel_order->tracking_code} has been approved by {$officerName}.";

            // ========== SMS NOTIFICATION ==========
            foreach ($applicants as $applicant) {
                if ($applicant->employee_information && ! empty($applicant->employee_information->contact_number)) {
                    if (! config('services.semaphore.api_key')) {
                        break;
                    }SendSmsJob::dispatch(
                        $applicant->employee_information->contact_number,
                        $message,
                        'travel_order_approved',
                        $applicant->id,
                        $this->from_oic ? $this->oic_signatory : auth()->id()
                    );
                }
            }
            // ========== SMS NOTIFICATION END ==========

            // ========== REALTIME NOTIFICATION ==========
            try {
                foreach ($applicants as $applicant) {
                    NotificationController::sendGeneralNotification(
                        'travel_order_approved',
                        'Travel Order Approved',
                        $message,
                        $applicant,
                        route('requisitioner.travel-orders.show', $this->travel_order),
                        $this->from_oic ? $this->oic_signatory : auth()->id()
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Realtime notification failed: '.$e->getMessage());
            }
            // ========== REALTIME NOTIFICATION END ==========
        }

        $this->dialog()->success(
            $title = 'Approved',
            $description = 'Travel order approved!',
            $icon = 'success',
        );
    }

    public function reject()
    {
        $this->validate(['rejectionNote' => 'required']);
        $this->modalRejection = false;
        $this->dialog()->id('custom')->confirm([
            'icon' => 'question',
            'iconColor' => 'text-primary-500',
            'accept' => [
                'label' => 'Proceed',
                'method' => 'rejectFinal',
            ],
        ]);
    }

    public function rejectFinal($rejectedDueToConversion = false)
    {
        if ($this->from_oic) {
            $this->travel_order->signatories()->updateExistingPivot($this->oic_signatory, ['is_approved' => 2]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order rejected as OIC for: '.User::find($this->oic_signatory)?->employee_information->full_name, 'user_id' => auth()->id()]);
            if (! $rejectedDueToConversion) {
                $this->travel_order->sidenotes()->create(['content' => 'Travel order Rejected for this/these reason/s : '.$this->rejectionNote, 'user_id' => auth()->id()]);
            }
            $officerName = User::find($this->oic_signatory)?->employee_information->full_name;
        } else {
            $this->travel_order->signatories()->updateExistingPivot(auth()->id(), ['is_approved' => 2]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order rejected.', 'user_id' => auth()->id()]);
            if (! $rejectedDueToConversion) {
                $this->travel_order->sidenotes()->create(['content' => 'Travel order Rejected for this/these reason/s : '.$this->rejectionNote, 'user_id' => auth()->id()]);
            }
            $officerName = auth()->user()->employee_information->full_name;
        }

        // Send SMS notifications to all applicants
        $applicants = $this->travel_order->applicants()->with('employee_information')->get();
        $message = "Your travel order with ref. no. {$this->travel_order->tracking_code} has been rejected by {$officerName}.";

        // ========== SMS NOTIFICATION ==========
        foreach ($applicants as $applicant) {
            if ($applicant->employee_information && ! empty($applicant->employee_information->contact_number)) {
                if (! config('services.semaphore.api_key')) {
                    break;
                }
                SendSmsJob::dispatch(
                    $applicant->employee_information->contact_number,
                    $message,
                    'travel_order_rejected',
                    $applicant->id,
                    $this->from_oic ? $this->oic_signatory : auth()->id()
                );
            }
        }
        // ========== SMS NOTIFICATION END ==========

        // ========== REALTIME NOTIFICATION ==========
        try {
            foreach ($applicants as $applicant) {
                NotificationController::sendGeneralNotification(
                    'travel_order_rejected',
                    'Travel Order Rejected',
                    $message,
                    $applicant,
                    route('requisitioner.travel-orders.show', $this->travel_order),
                    $this->from_oic ? $this->oic_signatory : auth()->id()
                );
            }
        } catch (\Exception $e) {
            \Log::error('Realtime notification failed: '.$e->getMessage());
        }
        // ========== REALTIME NOTIFICATION END ==========

        if (! $rejectedDueToConversion) {
            $this->dialog()->success(
                $title = 'Operation Completed',
                $description = 'Travel order rejected!',
                $icon = 'success',
            );
        }
        $this->travel_order->refresh();
    }

    private function hasCompleteSubmittedItineraries($itineraries = null): bool
    {
        if ($this->travel_order->travel_order_type_id !== TravelOrderType::OFFICIAL_BUSINESS) {
            return true;
        }

        $applicantIds = $this->travel_order->applicants()->pluck('users.id');

        if ($applicantIds->isEmpty()) {
            return true;
        }

        $submittedItineraryCount = ($itineraries ?? $this->travel_order->itineraries()->get())
            ->where('is_actual', false)
            ->whereIn('user_id', $applicantIds)
            ->filter(fn (Itinerary $itinerary) => filled($itinerary->submitted_at))
            ->unique('user_id')
            ->count();

        return $submittedItineraryCount === $applicantIds->count();
    }
}
