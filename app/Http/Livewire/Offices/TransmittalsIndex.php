<?php

namespace App\Http\Livewire\Offices;

use App\Models\DisbursementVoucher;
use App\Models\Transmittal;
use Livewire\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

/**
 * Transmittal management (item 3) for office staff.
 *
 * Create a transmittal from one or more DVs currently in the office, print the
 * transmittal form, record an acknowledgment, and print the acknowledgment
 * receipt. Open to all office groups (1-5); guarded in mount().
 */
class TransmittalsIndex extends Component implements HasTable
{
    use InteractsWithTable;

    public function mount()
    {
        if (! in_array(auth()->user()->employee_information?->office?->office_group_id, [1, 2, 3, 4, 5])) {
            abort(403, 'You are not allowed to access this page.');
        }
    }

    protected function getTableQuery()
    {
        return Transmittal::query()
            ->with(['prepared_by_user.employee_information'])
            ->withCount('disbursement_vouchers')
            ->latest();
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('transmittal_number')->label('Transmittal No.')->searchable(),
            TextColumn::make('recipient')->label('Transmitted To')->wrap()->searchable(),
            TextColumn::make('disbursement_vouchers_count')->label('No. of DVs'),
            TextColumn::make('prepared_by_user.employee_information.full_name')->label('Prepared By')->wrap(),
            BadgeColumn::make('status')->label('Status')
                ->getStateUsing(fn ($record) => $record->is_acknowledged ? 'Acknowledged' : 'Pending')
                ->colors([
                    'success' => 'Acknowledged',
                    'warning' => 'Pending',
                ]),
            TextColumn::make('created_at')->label('Date')->dateTime('M d, Y'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'acknowledged' => 'Acknowledged',
                ])
                ->query(function ($query, array $data) {
                    if (($data['value'] ?? null) === 'pending') {
                        $query->whereNull('acknowledged_at');
                    } elseif (($data['value'] ?? null) === 'acknowledged') {
                        $query->whereNotNull('acknowledged_at');
                    }
                    return $query;
                })
                ->label('Status'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create_transmittal')
                ->label('Create Transmittal')
                ->icon('ri-add-line')
                ->modalHeading('Create Transmittal')
                ->modalButton('Create Transmittal')
                ->modalWidth('3xl')
                ->form([
                    Select::make('disbursement_voucher_ids')
                        ->label('Disbursement Vouchers')
                        ->multiple()
                        ->required()
                        ->searchable()
                        ->options(fn () => DisbursementVoucher::query()
                            ->whereRelation('current_step', 'office_group_id', auth()->user()->employee_information->office->office_group_id)
                            ->with('voucher_subtype')
                            ->latest('submitted_at')
                            ->limit(500)
                            ->get()
                            ->mapWithKeys(fn ($dv) => [
                                $dv->id => $dv->tracking_number . ' — ' . ($dv->payee ?? 'N/A'),
                            ])
                            ->toArray())
                        ->helperText('Select one DV for an individual transmittal, or several for a batch.'),
                    TextInput::make('recipient')
                        ->label('Transmitted To')
                        ->required()
                        ->placeholder("e.g. Budget Office, COA, President's Office"),
                    Textarea::make('remarks')->label('Remarks (Optional)'),
                ])
                ->action(function (array $data) {
                    DB::beginTransaction();
                    $transmittal = Transmittal::create([
                        'transmittal_number' => Transmittal::generateTransmittalNumber(),
                        'recipient' => $data['recipient'],
                        'remarks' => $data['remarks'] ?? null,
                        'office_group_id' => auth()->user()->employee_information->office->office_group_id,
                        'prepared_by' => auth()->id(),
                    ]);
                    $transmittal->disbursement_vouchers()->sync($data['disbursement_voucher_ids']);
                    DB::commit();

                    Notification::make()
                        ->title('Transmittal ' . $transmittal->transmittal_number . ' created.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('view_items')
                    ->label('View Items')
                    ->icon('ri-list-check-2')
                    ->modalHeading('Transmittal Items')
                    ->modalWidth('3xl')
                    ->modalContent(fn ($record) => view('components.transmittals.items', [
                        'transmittal' => $record->load('disbursement_vouchers.voucher_subtype.voucher_type', 'disbursement_vouchers.disbursement_voucher_particulars'),
                    ])),
                Action::make('print')
                    ->label('Print Transmittal')
                    ->icon('ri-printer-line')
                    ->url(fn ($record) => route('office.transmittals.print', $record), true),
                Action::make('acknowledge')
                    ->label('Acknowledge')
                    ->icon('ri-check-double-line')
                    ->color('success')
                    ->modalHeading('Acknowledge Transmittal')
                    ->form([
                        TextInput::make('acknowledged_by')
                            ->label('Received By (Full Name)')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'acknowledged_at' => now(),
                            'acknowledged_by' => $data['acknowledged_by'],
                        ]);
                        Notification::make()->title('Transmittal acknowledged.')->success()->send();
                    })
                    ->visible(fn ($record) => ! $record->is_acknowledged),
                Action::make('print_acknowledgment')
                    ->label('Print Acknowledgment')
                    ->icon('ri-file-text-line')
                    ->url(fn ($record) => route('office.transmittals.acknowledgment', $record), true)
                    ->visible(fn ($record) => $record->is_acknowledged),
            ])->icon('ri-more-2-fill'),
        ];
    }

    public function render()
    {
        return view('livewire.offices.transmittals-index');
    }
}
