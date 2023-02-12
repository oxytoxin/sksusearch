<?php

namespace App\Http\Livewire\Requisitioner\LiquidationReports;

use DB;
use App\Models\Mot;
use Livewire\Component;
use Carbon\CarbonPeriod;
use App\Models\TravelOrder;
use App\Models\VoucherType;
use Illuminate\Support\Str;
use App\Models\TravelOrderType;
use App\Models\LiquidationReport;
use Illuminate\Support\HtmlString;
use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use App\Forms\Components\SlimRepeater;
use App\Models\Itinerary;
use App\Models\ItineraryEntry;
use Carbon\Carbon;
use Filament\Forms\Components\Builder;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Section;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;

class LiquidationReportsCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];
    public $disbursement_voucher;

    protected function getFormSchema()
    {
        return [
            Wizard::make([
                Step::make('Particulars')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('disbursement_voucher_id')
                                ->options(
                                    DisbursementVoucher::query()
                                        ->doesntHave('liquidation_report', 'and', function ($query) {
                                            $query->whereNull('cancelled_at');
                                        })
                                        ->whereRelation('voucher_subtype', 'voucher_type_id', 1)
                                        ->whereUserId(auth()->id())
                                        ->whereNotNull('cheque_number')
                                        ->pluck('tracking_number', 'id')
                                )
                                ->placeholder('Select cash advance')
                                ->searchable()
                                ->preload()
                                ->label('Cash Advance')
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function ($set, $state) {
                                    $this->disbursement_voucher = DisbursementVoucher::with('travel_order')->withSum('disbursement_voucher_particulars as total_amount', 'final_amount')->find($state);
                                    if ($this->disbursement_voucher) {
                                        if ($this->disbursement_voucher->travel_order) {
                                            $this->generateItineraryEntries($this->disbursement_voucher->travel_order->itineraries()->firstWhere('user_id', auth()->id()));
                                        }
                                        $set('signatory_id', $this->disbursement_voucher->signatory_id);
                                        $particulars = collect();
                                        foreach ($this->disbursement_voucher->disbursement_voucher_particulars as $key => $particular) {
                                            $purpose = str($particular->purpose);
                                            if ($key == 0) {
                                                $purpose = $purpose->prepend("To liquidate the cash advance granted for the following purpose(s):\n");
                                            }
                                            $particulars->push([
                                                'purpose' => $purpose->toString(),
                                                'amount' => $particular->final_amount
                                            ]);
                                        }
                                        $set('particulars', $particulars->toArray());
                                    }
                                }),
                            Select::make('signatory_id')
                                ->options(
                                    EmployeeInformation::pluck('full_name', 'user_id')
                                )
                                ->label('Signatory')
                                ->required()
                                ->searchable(),
                        ]),
                        Repeater::make('particulars')
                            ->schema([
                                Textarea::make('purpose')->required(),
                                TextInput::make('amount')->disabled(fn () => $this->disbursement_voucher?->travel_order_id)->numeric()->minValue(0)->required()->reactive()->afterStateUpdated(function ($set, $get) {
                                    try {
                                        $particulars = collect($this->data['particulars']);
                                        if ($particulars->sum('amount') > $this->disbursement_voucher->total_amount) {
                                            $this->data['refund_particulars'] = [];
                                        } else {
                                            $this->data['reimbursement_waived'] = true;
                                        }
                                    } catch (\Throwable $th) {
                                    }
                                }),
                            ])
                            ->disableItemDeletion(fn () => $this->disbursement_voucher?->travel_order_id)
                            ->disableItemCreation(fn () => $this->disbursement_voucher?->travel_order_id)
                            ->columns(2)
                            ->visible(fn () => $this->disbursement_voucher),
                        Section::make('Actual Itinerary')
                            ->visible(fn () => $this->disbursement_voucher?->travel_order_id)
                            ->schema([
                                Card::make([
                                    Placeholder::make('travel_order_details')
                                        ->content(fn () => view('components.travel_orders.travel-order-details', [
                                            'travel_order' => $this->disbursement_voucher?->travel_order,
                                            'itinerary_entries' => $this->data['itinerary_entries'] ?? [],
                                        ])),
                                ]),
                                Builder::make('itinerary_entries')
                                    // ->disableItemMovement()
                                    ->blocks([
                                        Block::make('new_entry')->schema([
                                            Grid::make(2)->schema([
                                                Fieldset::make('Coverage')->schema([
                                                    Flatpickr::make('date')
                                                        ->disableTime()
                                                        ->required()
                                                        ->disabled()
                                                        ->columnSpan(1),
                                                    Grid::make([
                                                        'sm' => 4,
                                                        'md' => 4,
                                                    ])
                                                        ->schema([
                                                            Toggle::make('breakfast')->inline(false)->reactive()->columnSpan(1),
                                                            Toggle::make('lunch')->inline(false)->reactive()->columnSpan(1),
                                                            Toggle::make('dinner')->inline(false)->reactive()->columnSpan(1),
                                                            Toggle::make('lodging')->inline(false)->reactive()->columnSpan(1),
                                                        ])->columnSpan(1),
                                                ])->columnSpan(1),
                                                Fieldset::make('Total Amount')->schema([
                                                    Toggle::make('has_per_diem')
                                                        ->label('Has Per Diem')
                                                        ->reactive(),
                                                    TextInput::make('per_diem')->disabled(),
                                                    TextInput::make('total_expenses')->disabled()->default(0),
                                                ])->columns(1)->columnSpan(1),
                                            ]),
                                            Repeater::make('itinerary_entries')->schema([
                                                Grid::make([
                                                    'sm' => 1,
                                                    'md' => 2,
                                                    'lg' => 6,
                                                ])
                                                    ->schema([
                                                        Select::make('mot_id')
                                                            ->options(Mot::pluck('name', 'id'))
                                                            ->label('Mode of Transport')
                                                            ->required(),
                                                        TextInput::make('place')->required(),
                                                        Flatpickr::make('departure_time')
                                                            ->disableDate()
                                                            ->required(),
                                                        Flatpickr::make('arrival_time')
                                                            ->disableDate()
                                                            ->afterOrEqual('departure_time')
                                                            ->required(),
                                                        TextInput::make('transportation_expenses')
                                                            ->label('Transportation')->default(0)->required()->numeric()->reactive(),
                                                        TextInput::make('other_expenses')
                                                            ->label('Others')->default(0)->numeric()->reactive(),
                                                    ])
                                            ]),
                                        ]),
                                    ]),
                            ]),
                    ]),
                Step::make('Print Actual Itinerary')
                    ->schema([
                        Placeholder::make('actual_itinerary')
                            ->content(
                                function () {
                                    if ($this->disbursement_voucher->travel_order_id) {
                                        $coverage = [];
                                        foreach ($this->data['itinerary_entries'] as $entry) {
                                            $coverage[] = [
                                                'date' => $entry['data']['date'],
                                                'per_diem' => $entry['data']['per_diem'],
                                                'total_expenses' => $entry['data']['total_expenses'],
                                                'breakfast' => $entry['data']['breakfast'],
                                                'lunch' => $entry['data']['lunch'],
                                                'dinner' => $entry['data']['dinner'],
                                                'lodging' => $entry['data']['lodging'],
                                            ];
                                        }
                                        $itinerary = Itinerary::make([
                                            'is_actual' => true,
                                            'user_id' => auth()->id(),
                                            'travel_order_id' => $this->disbursement_voucher->travel_order_id,
                                            'coverage' => $coverage,
                                        ]);
                                        $itinerary_entries = [];
                                        foreach ($this->data['itinerary_entries'] as $itinerary_entry) {
                                            foreach ($itinerary_entry['data']['itinerary_entries'] as $entry) {
                                                $itinerary_entries[] = ItineraryEntry::make([
                                                    'date' => $itinerary_entry['data']['date'],
                                                    'mot_id' => $entry['mot_id'],
                                                    'place' => $entry['place'],
                                                    'departure_time' => $entry['departure_time'],
                                                    'arrival_time' => $entry['arrival_time'],
                                                    'transportation_expenses' => $entry['transportation_expenses'],
                                                    'other_expenses' => $entry['other_expenses'],
                                                ]);
                                            }
                                        }
                                        return view('livewire.requisitioner.itinerary.itinerary-print', [
                                            'itinerary' => $itinerary,
                                            'itinerary_entries' => $itinerary_entries,
                                            'travel_order' => $this->disbursement_voucher?->travel_order,
                                            'immediate_signatory' => $this->disbursement_voucher?->travel_order?->signatories()->with('employee_information')->first(),
                                        ]);
                                    }
                                    return new HtmlString('Itinerary not required by this liquidation report.');
                                }
                            )->disableLabel()
                    ]),
                Step::make('Refund / Reimbursement')
                    ->schema([
                        Placeholder::make('gross_amount')
                            ->view('components.liquidation_reports.liquidation-details')
                            ->visible(fn ($get) => $get('disbursement_voucher_id')),
                        Fieldset::make('Refund')
                            ->schema([
                                SlimRepeater::make('refund_particulars')->schema([
                                    TextInput::make('or_number')->required()->validationAttribute('OR Number')->label('OR Number')->disableLabel(),
                                    DatePicker::make('date')->required()->validationAttribute('Date')->withoutTime()->label('OR Date')->disableLabel(),
                                    TextInput::make('amount')->required()->validationAttribute('Amount')->numeric()->label('Amount')->reactive()->disableLabel(),
                                ])->columns(3)
                            ])
                            ->columns(1)
                            ->visible(function ($get) {
                                try {
                                    if ($this->disbursement_voucher?->travel_order_id) {
                                        $expenses = collect($this->data['itinerary_entries'])->sum('data.total_expenses');
                                        return $this->disbursement_voucher && $expenses < $this->disbursement_voucher->total_amount;
                                    }
                                    $particulars = collect($this->data['particulars']);
                                    return $this->disbursement_voucher && $particulars->sum('amount') < $this->disbursement_voucher->total_amount;
                                } catch (\Throwable $th) {
                                    return false;
                                }
                            }),
                        Fieldset::make('Reimbursement')
                            ->schema([
                                Radio::make('reimbursement_waived')
                                    ->options([
                                        0 => 'Yes',
                                        1 => 'No'
                                    ])
                                    ->default(1)
                                    ->label('Process Reimbursement')
                            ])
                            ->visible(function ($get) {
                                try {
                                    if ($this->disbursement_voucher?->travel_order_id) {
                                        $expenses = collect($this->data['itinerary_entries'])->sum('data.total_expenses');
                                        return $this->disbursement_voucher && $expenses > $this->disbursement_voucher->total_amount;
                                    }
                                    $particulars = collect($this->data['particulars']);
                                    return $this->disbursement_voucher && $particulars->sum('amount') > $this->disbursement_voucher->total_amount;
                                } catch (\Throwable $th) {
                                    return false;
                                }
                            }),
                    ])
                    ->afterValidation(function () {
                        try {
                            $particulars = collect($this->data['particulars']);
                            $refund_particulars = collect($this->data['refund_particulars']);
                            $cheque_amount = $this->disbursement_voucher->total_suggested_amount > 0 ? $this->disbursement_voucher->total_suggested_amount : $this->disbursement_voucher->total_amount;
                            if ($cheque_amount > $particulars->sum('amount')) {
                                if (($refund_particulars->sum('amount') ?? 0) != ($cheque_amount - $particulars->sum('amount') ?? 0)) {
                                    Notification::make()->title('Refund Error')->body('Refunded amount must be equal to the amount to be refunded.')->danger()->send();
                                    throw ValidationException::withMessages([
                                        'refund_amount' => 'Refunded amount must be equal to the amount to be refunded.'
                                    ]);
                                }
                            }
                        } catch (\Throwable $th) {
                        }
                    }),
                Step::make('Related Documents')
                    ->schema([
                        Placeholder::make('related_documents')
                            ->disableLabel()
                            ->content(fn ($record) => view('components.liquidation_reports.related-documents', [
                                'voucher_subtype' => $this->disbursement_voucher?->voucher_subtype
                            ]))

                    ]),
                Step::make('Preview')
                    ->schema([
                        Placeholder::make('preview')
                            ->disableLabel()
                            ->content(function () {
                                return view('components.liquidation_reports.liquidation-report-preview');
                            })
                    ])
            ])->submitAction(new HtmlString(view('components.forms.save-button')->render()))
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function mount()
    {
        $this->form->fill();
        $this->data['refund_particulars'] = [];
        if (request('disbursement_voucher')) {
            $dv = DisbursementVoucher::with('travel_order')->withSum('disbursement_voucher_particulars as total_amount', 'final_amount')->whereRelation('voucher_subtype', 'voucher_type_id', 1)->findOrFail(request('disbursement_voucher'));
            if ($dv->user_id != auth()->id() || $dv->liquidation_report()->whereNull('cancelled_at')->exists()) {
                abort(403);
            }
            $this->disbursement_voucher = $dv;
            $this->data['disbursement_voucher_id'] = $dv->id;
            $this->data['signatory_id'] = $this->disbursement_voucher->signatory_id;

            $particulars = collect();
            foreach ($this->disbursement_voucher->disbursement_voucher_particulars as $key => $particular) {
                $purpose = str($particular->purpose);
                if ($key == 0) {
                    $purpose = $purpose->prepend("To liquidate the cash advance granted for the following purpose(s):\n");
                }
                $particulars->push([
                    'purpose' => $purpose->toString(),
                    'amount' => $particular->final_amount
                ]);
            }
            $this->data['particulars'] = $particulars->toArray();
            if ($dv->travel_order_id) {
                $this->generateItineraryEntries($dv->travel_order->itineraries()->firstWhere('user_id', auth()->id()));
            }
        }
    }

    public function render()
    {
        if ($this->disbursement_voucher?->travel_order_id) {
            foreach ($this->data['itinerary_entries'] as  $key => $entry) {
                $original_per_diem = $entry['data']['original_per_diem'] ?? $this->disbursement_voucher->travel_order->philippine_region->dte->amount;
                $per_diem = $original_per_diem;
                if (!$entry['data']['has_per_diem']) {
                    $per_diem = 0;
                } else {
                    if ($entry['data']['breakfast']) {
                        $per_diem -= $original_per_diem * 0.1;
                    }
                    if ($entry['data']['lunch']) {
                        $per_diem -= $original_per_diem * 0.1;
                    }
                    if ($entry['data']['dinner']) {
                        $per_diem -= $original_per_diem * 0.1;
                    }
                    if ($entry['data']['lodging']) {
                        $per_diem -= $original_per_diem * 0.5;
                    }
                }

                $transportation_expenses = 0;
                $other_expenses = 0;
                foreach ($entry['data']['itinerary_entries'] as $expense) {
                    $transportation_expenses += $expense['transportation_expenses'] == '' ? 0 : $expense['transportation_expenses'];
                    $other_expenses += $expense['other_expenses'] == '' ? 0 : $expense['other_expenses'];
                }
                $this->data['itinerary_entries'][$key]['data']['per_diem'] = $per_diem;
                $this->data['itinerary_entries'][$key]['data']['total_expenses'] = $transportation_expenses + $other_expenses + $per_diem;
            }
            $this->data['particulars'][0]['amount'] = collect($this->data['itinerary_entries'])->sum('data.total_expenses');
        }
        return view('livewire.requisitioner.liquidation-reports.liquidation-reports-create');
    }

    public function save()
    {
        $this->form->validate();
        DB::beginTransaction();
        if ($this->disbursement_voucher->travel_order_id) {
            $coverage = [];
            foreach ($this->data['itinerary_entries'] as $entry) {
                $coverage[] = [
                    'date' => $entry['data']['date'],
                    'per_diem' => $entry['data']['per_diem'],
                    'total_expenses' => $entry['data']['total_expenses'],
                    'breakfast' => $entry['data']['breakfast'],
                    'lunch' => $entry['data']['lunch'],
                    'dinner' => $entry['data']['dinner'],
                    'lodging' => $entry['data']['lodging'],
                ];
            }

            $itinerary = Itinerary::create([
                'is_actual' => true,
                'user_id' => auth()->id(),
                'travel_order_id' => $this->disbursement_voucher->travel_order_id,
                'coverage' => $coverage,
            ]);

            foreach ($this->data['itinerary_entries'] as $itinerary_entry) {
                foreach ($itinerary_entry['data']['itinerary_entries'] as $entry) {
                    $itinerary->itinerary_entries()->create([
                        'date' => $itinerary_entry['data']['date'],
                        'mot_id' => $entry['mot_id'],
                        'place' => $entry['place'],
                        'departure_time' => $entry['departure_time'],
                        'arrival_time' => $entry['arrival_time'],
                        'transportation_expenses' => $entry['transportation_expenses'],
                        'other_expenses' => $entry['other_expenses'],
                    ]);
                }
            }
        }
        $lr = LiquidationReport::create([
            'tracking_number' => LiquidationReport::generateTrackingNumber(),
            'disbursement_voucher_id' => $this->data['disbursement_voucher_id'],
            'user_id' => auth()->id(),
            'signatory_id' => $this->data['signatory_id'],
            'reimbursement_waived' => $this->data['reimbursement_waived'],
            'report_date' => today(),
            'particulars' => $this->data['particulars'],
            'refund_particulars' => collect($this->data['refund_particulars'])->values()->toArray(),
            'current_step_id' => 3000,
            'previous_step_id' => 2000,
        ]);

        $lr->activity_logs()->create([
            'description' => $lr->current_step->process . ' ' . $lr->signatory->employee_information->full_name . ' ' . $lr->current_step->sender,
        ]);

        DB::commit();
        Notification::make()->title('Liquidation Report Submitted!')->success()->send();
        return redirect()->route('requisitioner.liquidation-reports.index');
    }

    private function generateItineraryEntries($old_itinerary)
    {
        $entries = [];
        $itinerary_entries = $old_itinerary->itinerary_entries;
        $original_per_diem = $this->disbursement_voucher->travel_order->philippine_region->dte->amount;
        foreach ($old_itinerary->coverage as $key => $coverage) {
            if ($this->disbursement_voucher->travel_order->date_to == Carbon::make($coverage['date'])) {
                $original_per_diem /= 2;
            }
            $daily_entries = $itinerary_entries->where('date', Carbon::make($coverage['date']));
            $daily_itinerary_entries = [];
            foreach ($daily_entries as $k => $entry) {
                $daily_itinerary_entries[] =  [
                    'mot_id' => $entry->mot_id,
                    'place' => $entry->place,
                    'departure_time' => $entry->departure_time,
                    'arrival_time' => $entry->arrival_time,
                    'transportation_expenses' => $entry->transportation_expenses,
                    'other_expenses' => $entry->other_expenses,
                ];
            }
            $entries[Str::uuid()->toString()] = [
                'type' => 'new_entry',
                'data' => [
                    'date' => $coverage['date'],
                    'per_diem' => $coverage['per_diem'],
                    'original_per_diem' => $original_per_diem,
                    'has_per_diem' => $coverage['per_diem'] > 0,
                    'total_expenses' => $coverage['total_expenses'],
                    'breakfast' => $coverage['breakfast'],
                    'lunch' => $coverage['lunch'],
                    'dinner' => $coverage['dinner'],
                    'lodging' => $coverage['lodging'],
                    'itinerary_entries' => $daily_itinerary_entries,
                ],
            ];
        }
        $this->data['itinerary_entries'] = $entries;
    }
}
