<?php

namespace App\Http\Livewire\Offices;

use App\Models\BatchTransmittal;
use App\Services\DisbursementVouchers\DisbursementVoucherWorkflowService;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BatchTransmittalIndex extends Component implements HasTable
{
    use InteractsWithTable;

    public $tab = 'incoming';

    public function mount()
    {
        if (!in_array(auth()->user()->employee_information?->office->office_group_id, [1, 2, 3, 4, 5])) {
            abort(403);
        }
    }

    protected function getTableQuery()
    {
        $officeGroupId = auth()->user()->employee_information->office->office_group_id;

        if ($this->tab === 'incoming') {
            return BatchTransmittal::query()
                ->whereHas('items.disbursement_voucher', function ($q) use ($officeGroupId) {
                    $q->whereRelation('current_step', 'office_group_id', $officeGroupId);
                })
                ->whereNotNull('forwarded_at')
                ->whereNull('received_at')
                ->latest('forwarded_at');
        }

        return BatchTransmittal::query()
            ->where('office_group_id', $officeGroupId)
            ->latest();
    }

    public function updatedTab()
    {
        $this->resetPage();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('serial_number')->label('Transmittal No.')->sortable(),
            TextColumn::make('from_office_name')->label('From')->wrap(),
            TextColumn::make('to_office_name')->label('To')->wrap(),
            TextColumn::make('items_count')->counts('items')->label('DVs'),
            TextColumn::make('created_by_user.employee_information.full_name')->label('Created By')->wrap(),
            TextColumn::make('forwarded_at')->label('Forwarded')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('M d, Y g:i A') : '—')
                ->sortable(),
            TextColumn::make('received_at')->label('Received')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('M d, Y g:i A') : '—'),
            TextColumn::make('status')->label('Status')
                ->getStateUsing(function ($record) {
                    if ($record->received_at) return 'Received';
                    if ($record->forwarded_at) return 'Forwarded';
                    return 'Draft';
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('receive_batch')
                ->label('Receive Batch')
                ->button()
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(fn ($record) => $this->tab === 'incoming' && $record->forwarded_at && !$record->received_at)
                ->requiresConfirmation()
                ->modalHeading('Receive Batch')
                ->modalSubheading('Are you sure you want to receive all DVs in this batch?')
                ->action(function (BatchTransmittal $record) {
                    $workflowService = app(DisbursementVoucherWorkflowService::class);
                    $received = 0;
                    $skipped = 0;

                    DB::transaction(function () use ($record, $workflowService, &$received, &$skipped) {
                        foreach ($record->items()->with('disbursement_voucher.current_step')->get() as $item) {
                            $dv = $item->disbursement_voucher;
                            if ($dv->current_step->process === 'Forwarded to') {
                                $workflowService->receive($dv, auth()->user());
                                $received++;
                            } else {
                                $skipped++;
                            }
                        }
                        $record->update([
                            'received_at' => now(),
                            'received_by' => auth()->id(),
                        ]);
                    });

                    $msg = "Batch received. {$received} DV(s) received.";
                    if ($skipped > 0) {
                        $msg .= " {$skipped} DV(s) skipped (already received or returned).";
                    }
                    Notification::make()->title($msg)->success()->send();
                }),
            Action::make('view')
                ->url(fn ($record) => route('office.batch-transmittal.show', $record))
                ->icon('heroicon-o-eye'),
            Action::make('print')
                ->url(fn ($record) => route('office.batch-transmittal.print', $record))
                ->icon('heroicon-o-printer')
                ->openUrlInNewTab(),
        ];
    }

    public function render()
    {
        return view('livewire.offices.batch-transmittal-index');
    }
}
