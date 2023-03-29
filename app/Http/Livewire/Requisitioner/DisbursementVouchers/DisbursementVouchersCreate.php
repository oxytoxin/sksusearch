<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Str;
use App\Models\Mop;
use App\Models\Mot;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\WaterMeter;
use App\Models\TravelOrder;
use App\Models\VoucherSubType;
use Illuminate\Support\Carbon;
use App\Models\TravelOrderType;
use App\Models\ElectricityMeter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use App\Models\InternetAccountNumber;
use App\Models\Itinerary;
use App\Models\ItineraryEntry;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use App\Models\TelephoneAccountNumber;
use App\Models\TravelCompletedCertificate;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Concerns\InteractsWithForms;

class DisbursementVouchersCreate extends Component implements HasForms
{
    use InteractsWithForms;

    #region Variables
    public $other_expenses = [];

    public $total_expense;

    public $total_utility;

    public $electricity_utility_particulars = [];

    public $electricity_utility_type;

    public $electricity_consumption;

    public $electricity_cost;

    public $water_utility_particulars = [];

    public $water_utility_type;

    public $water_consumption;

    public $water_cost;

    public $fuel_utility_particulars = [];

    public $fuel_utility_type;

    public $fuel_consumption;

    public $fuel_cost;

    public $telephone_utility_particulars = [];

    public $telephone_account_number;

    public $telephone_amount;

    public $internet_utility_particulars = [];

    public $internet_account_number;

    public $internet_amount;

    public $tracking_number;

    public $travel_order_id;

    public $disbursement_voucher_particulars = [];

    public $itinerary_entries = [];

    public $payee;

    public $mop_id;

    public $signatory_id;

    public VoucherSubType $voucher_subtype;
    // ctc

    public $condition;
    public $amount;
    public $or_number;
    public $explanation;

    #endregion

    protected function getFormSchema()
    {
        return [
            Wizard::make([
                Step::make('DV Main Information Form')
                    ->description('Fill up the form for the disbursement voucher.')
                    ->schema([
                        Card::make()->schema([
                            Select::make('voucher_subtype_id')
                                ->label('Disbursement Voucher for')
                                ->options(VoucherSubType::with('voucher_type')->get()->map(fn ($v) => ['id' => $v->id, 'name' => "{$v->voucher_type->name} - {$v->name}"])->pluck('name', 'id'))
                                ->disabled()
                                ->default($this->voucher_subtype->id),
                            Select::make('travel_order_id')
                                ->label('Travel Order')
                                ->searchable()
                                ->preload()
                                ->visible(fn () => in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
                                ->required(fn () => in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
                                ->options(TravelOrder::where(function ($q) {
                                    $q->approved()
                                        ->whereHas('itineraries', function ($query) {
                                            $query->whereUserId(auth()->id());
                                        })
                                        ->where('travel_order_type_id', TravelOrderType::OFFICIAL_BUSINESS);
                                })->orWhere(function ($q) {
                                    $q->approved()->where('travel_order_type_id', TravelOrderType::OFFICIAL_TIME);
                                })
                                    ->whereDoesntHave('disbursement_vouchers', function ($q) {
                                        $q->where('user_id', auth()->id())->whereNotNull('cancelled_at');
                                    })
                                    ->pluck('tracking_code', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($set, $state) {
                                    $to = TravelOrder::find($state);
                                    if ($to && $to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                                        $itinerary = $to->itineraries()->whereUserId(auth()->id())->first();
                                        $this->generateItineraryEntries($itinerary, $to);
                                        $amount = $to->registration_amount;
                                        foreach ($itinerary['coverage'] as $entry) {
                                            $amount += $entry['total_expenses'];
                                        }

                                        $set('disbursement_voucher_particulars', [
                                            [
                                                'purpose' => $to->purpose,
                                                'responsibility_center' => '',
                                                'mfo_pap' => '',
                                                'amount' => $amount,
                                            ],
                                        ]);
                                    } else {
                                        $set('disbursement_voucher_particulars', [
                                            [
                                                'purpose' => $to->purpose,
                                                'responsibility_center' => '',
                                                'mfo_pap' => '',
                                                'amount' => 0,
                                            ],
                                        ]);
                                    }
                                }),
                            Radio::make('payee_mode')
                                ->label('Payee Mode')
                                ->options([
                                    'self' => 'Self',
                                    'others' => 'Others',
                                ])
                                ->visible(fn () => !in_array($this->voucher_subtype->voucher_type_id, [1, 2]))
                                ->default('self')
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state == 'self') {
                                        $set('payee', auth()->user()->employee_information->full_name);
                                    } else {
                                        $set('payee', '');
                                    }
                                })
                                ->inline()
                                ->reactive(),
                            Grid::make(2)->schema([
                                TextInput::make('payee')
                                    ->disabled(fn ($get) => $get('payee_mode') == 'self' || in_array($this->voucher_subtype->voucher_type_id, [1, 2]))
                                    ->required()
                                    ->placeholder('Enter payee name')
                                    ->default(auth()->user()->employee_information->full_name),
                                Select::make('mop_id')
                                    ->label('Mode of Payment')
                                    ->options(Mop::pluck('name', 'id')),
                            ]),
                            #region Utilities
                            //Electricity, Water, Fuel (start)
                            Repeater::make('electricity_utility_particulars')
                                ->columns(5)
                                ->schema([
                                    Select::make('electricity_utility_type')
                                        ->label('Meter Number')
                                        ->options(ElectricityMeter::pluck('meter_number', 'id'))
                                        ->required(),
                                    TextInput::make('electricity_consumption')
                                        ->label('Kilowatt-hour Consumpition (kWh)')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            if ($state == '' || $state == null) {
                                                $consumption = 0;
                                                $cost = $get('electricity_cost');
                                            } else if ($get('electricity_cost') == '' || ($get('electricity_cost') == null)) {
                                                $cost = 0;
                                                $consumption = $state;
                                            } else if (($state == '' || $state == null) && ($get('electricity_cost') == '' || $get('electricity_cost') == null)) {
                                                $cost = 0;
                                                $consumption = 0;
                                            } else {
                                                $cost = $state;
                                                $consumption = $get('electricity_cost');
                                            }
                                            $set('electricity_total', round($consumption * $cost, 2));
                                            $utility_particulars = collect($this->electricity_utility_particulars);
                                            $filtered = $utility_particulars->filter(function ($item) {
                                                return !is_null($item['electricity_total']) && $item['electricity_total'] !== "";
                                            });

                                            if ($filtered->isNotEmpty()) {
                                                $utility_sum = $filtered->sum('electricity_total');
                                            } else {
                                                $utility_sum = 0;
                                            }

                                            $other_expense = collect($this->other_expenses);
                                            $filtered_expense = $other_expense->filter(function ($item) {
                                                return !is_null($item['amount']) && $item['amount'] !== "";
                                            });

                                            if ($filtered_expense->isNotEmpty()) {
                                                $other_expense_sum = $filtered_expense->sum('amount');
                                            } else {
                                                $other_expense_sum = 0;
                                            }

                                            $total = $utility_sum + $other_expense_sum;
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' =>  round($total, 2),
                                                ],
                                            ]);
                                        })
                                        ->required(),
                                    TextInput::make('electricity_cost')
                                        ->label('Cost per kilowatt-hour (kWh)')
                                        ->numeric()
                                        ->reactive()
                                        ->default(0)
                                        ->lazy()
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            if ($state == '' || $state == null) {
                                                $consumption = $get('electricity_consumption');
                                                $cost = 0;
                                            } else if ($get('electricity_consumption') == ''  || $get('electricity_consumption') == null) {
                                                $cost = $state;
                                                $consumption = 0;
                                            } else if (($state == '' || $state == null) && ($get('electricity_consumption') == '' || $get('electricity_consumption') == null)) {
                                                $cost = 0;
                                                $consumption = 0;
                                            } else {
                                                $cost = $state;
                                                $consumption = $get('electricity_consumption');
                                            }
                                            $set('electricity_total', round($cost * $consumption, 2));
                                            $utility_particulars = collect($this->electricity_utility_particulars);
                                            $filtered = $utility_particulars->filter(function ($item) {
                                                return !is_null($item['electricity_total']) && $item['electricity_total'] !== "";
                                            });

                                            if ($filtered->isNotEmpty()) {
                                                $utility_sum = $filtered->sum('electricity_total');
                                            } else {
                                                $utility_sum = 0;
                                            }

                                            $other_expense = collect($this->other_expenses);
                                            $filtered_expense = $other_expense->filter(function ($item) {
                                                return !is_null($item['amount']) && $item['amount'] !== "";
                                            });

                                            if ($filtered_expense->isNotEmpty()) {
                                                $other_expense_sum = $filtered_expense->sum('amount');
                                            } else {
                                                $other_expense_sum = 0;
                                            }

                                            $total = $utility_sum + $other_expense_sum;
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' => round($total, 2),
                                                ],
                                            ]);
                                        })->required(),
                                    TextInput::make('bill_number')
                                        ->label('Bill No.')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('electricity_total')
                                        ->label('Total')
                                        ->numeric()
                                        ->reactive()
                                        ->default(0)
                                        ->disabled(),
                                ])
                                ->label('Utility Particulars')
                                ->createItemButtonLabel('Add New Row')
                                ->visible(fn ($get) => in_array($this->voucher_subtype->id, [27])),

                            Repeater::make('water_utility_particulars')
                                ->columns(5)
                                ->schema([
                                    Select::make('water_utility_type')
                                        ->label('Meter Number')
                                        ->options(WaterMeter::pluck('meter_number', 'id'))
                                        ->required(),
                                    TextInput::make('water_consumption')
                                        ->label('Cubic Metre Consumption')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            if ($state == '' || $state == null) {
                                                $consumption = 0;
                                                $cost = $get('water_cost');
                                            } else if ($get('water_cost') == '' || ($get('water_cost') == null)) {
                                                $cost = 0;
                                                $consumption = $state;
                                            } else if (($state == '' || $state == null) && ($get('water_cost') == '' || $get('water_cost') == null)) {
                                                $cost = 0;
                                                $consumption = 0;
                                            } else {
                                                $cost = $state;
                                                $consumption = $get('water_cost');
                                            }
                                            $total_consumption = $consumption * $cost;
                                            $set('water_total', round($total_consumption, 2));
                                            $utility_particulars = collect($this->water_utility_particulars);
                                            $other_expense = collect($this->other_expenses);
                                            $total = round($utility_particulars->sum('water_total') + $other_expense->sum('amount'), 2);
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' =>  round($total, 2),
                                                ],
                                            ]);
                                        })
                                        ->required(),
                                    TextInput::make('water_cost')
                                        ->label('Cost per Cubic Metre')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            if ($state == '' || $state == null) {
                                                $consumption = $get('water_consumption');
                                                $cost = 0;
                                            } else if ($get('water_consumption') == ''  || $get('water_consumption') == null) {
                                                $cost = $state;
                                                $consumption = 0;
                                            } else if (($state == '' || $state == null) && ($get('water_consumption') == '' || $get('water_consumption') == null)) {
                                                $cost = 0;
                                                $consumption = 0;
                                            } else {
                                                $cost = $state;
                                                $consumption = $get('water_consumption');
                                            }
                                            $total_consumption = $cost * $consumption;
                                            $set('water_total', round($total_consumption, 2));
                                            $utility_particulars = collect($this->water_utility_particulars);
                                            $other_expense = collect($this->other_expenses);
                                            $total = round($utility_particulars->sum('water_total'), 2) + round($other_expense->sum('amount'), 2);
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' => round($total, 2),
                                                ],
                                            ]);
                                        })->required(),
                                    TextInput::make('bill_number')
                                        ->label('Bill No.')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('water_total')
                                        ->label('Total')
                                        ->numeric()
                                        ->reactive()
                                        ->default(0)
                                        ->disabled(),
                                ])
                                ->label('Utility Particulars')
                                ->createItemButtonLabel('Add New Row')
                                ->visible(fn ($get) => in_array($this->voucher_subtype->id, [70])),

                            Repeater::make('fuel_utility_particulars')
                                ->columns(4)
                                ->schema([
                                    Select::make('fuel_utility_type')
                                        ->label('Vehicle')
                                        ->options(Vehicle::pluck('model', 'id'))
                                        ->required(),
                                    TextInput::make('fuel_consumption')
                                        ->label('Fuel Consumption (Liters)')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            if ($state == '' || $state == null) {
                                                $consumption = 0;
                                                $cost = $get('fuel_cost');
                                            } else if ($get('fuel_cost') == '' || ($get('fuel_cost') == null)) {
                                                $cost = 0;
                                                $consumption = $state;
                                            } else if (($state == '' || $state == null) && ($get('fuel_cost') == '' || $get('fuel_cost') == null)) {
                                                $cost = 0;
                                                $consumption = 0;
                                            } else {
                                                $cost = $state;
                                                $consumption = $get('fuel_cost');
                                            }
                                            $total_consumption = $consumption * $cost;
                                            $set('fuel_total', round($total_consumption, 2));
                                            $utility_particulars = collect($this->fuel_utility_particulars);
                                            $other_expense = collect($this->other_expenses);
                                            $total = round($utility_particulars->sum('fuel_total') + $other_expense->sum('amount'), 2);
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' =>  round($total, 2),
                                                ],
                                            ]);
                                        })
                                        ->required(),
                                    TextInput::make('fuel_cost')
                                        ->label('Cost per Liter')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            if ($state == '' || $state == null) {
                                                $consumption = $get('fuel_consumption');
                                                $cost = 0;
                                            } else if ($get('fuel_consumption') == ''  || $get('fuel_consumption') == null) {
                                                $cost = $state;
                                                $consumption = 0;
                                            } else if (($state == '' || $state == null) && ($get('fuel_consumption') == '' || $get('fuel_consumption') == null)) {
                                                $cost = 0;
                                                $consumption = 0;
                                            } else {
                                                $cost = $state;
                                                $consumption = $get('fuel_consumption');
                                            }
                                            $total_consumption = $cost * $consumption;
                                            $set('fuel_total', round($total_consumption, 2));
                                            $utility_particulars = collect($this->fuel_utility_particulars);
                                            $other_expense = collect($this->other_expenses);
                                            $total = round($utility_particulars->sum('fuel_total') + $other_expense->sum('amount'), 2);
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' => round($total, 2),
                                                ],
                                            ]);
                                        })->required(),
                                    TextInput::make('fuel_total')
                                        ->label('Total')
                                        ->numeric()
                                        ->reactive()
                                        ->default(0)
                                        ->disabled(),
                                ])
                                ->label('Utility Particulars')
                                ->createItemButtonLabel('Add New Row')
                                ->visible(fn ($get) => in_array($this->voucher_subtype->id, [71])),

                            Repeater::make('telephone_utility_particulars')
                                ->columns(2)
                                ->schema([
                                    Select::make('telephone_account_number')
                                        ->label('Account Number')
                                        ->options(TelephoneAccountNumber::pluck('account_number', 'id'))
                                        ->required(),
                                    TextInput::make('telephone_amount')
                                        ->label('Amount')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            $utility_particulars = collect($this->telephone_utility_particulars);
                                            $other_expense = collect($this->other_expenses);
                                            $total = round($utility_particulars->sum('telephone_amount') + $other_expense->sum('amount'), 2);
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' => round($total, 2),
                                                ],
                                            ]);
                                        })->required(),
                                ])
                                ->label('Utility Particulars')
                                ->createItemButtonLabel('Add New Row')
                                ->visible(fn ($get) => in_array($this->voucher_subtype->id, [74])),

                            Repeater::make('internet_utility_particulars')
                                ->columns(2)
                                ->schema([
                                    Select::make('internet_account_number')
                                        ->label('Account Number')
                                        ->options(InternetAccountNumber::pluck('account_number', 'id'))
                                        ->required(),
                                    TextInput::make('internet_amount')
                                        ->label('Amount')
                                        ->numeric()
                                        ->reactive()
                                        ->lazy()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get, $state) {
                                            $utility_particulars = collect($this->internet_utility_particulars);
                                            $other_expense = collect($this->other_expenses);
                                            $total = round($utility_particulars->sum('internet_amount') + $other_expense->sum('amount'), 2);
                                            $set('../../disbursement_voucher_particulars', [
                                                [
                                                    'purpose' => $get('../../purpose'),
                                                    'responsibility_center' => '',
                                                    'mfo_pap' => '',
                                                    'amount' => round($total, 2),
                                                ],
                                            ]);
                                        })->required(),
                                ])
                                ->label('Utility Particulars')
                                ->createItemButtonLabel('Add New Row')
                                ->visible(fn ($get) => in_array($this->voucher_subtype->id, [75])),

                            Repeater::make('other_expenses')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name'),
                                    TextInput::make('amount')
                                        ->reactive()
                                        ->lazy()
                                        ->numeric()
                                        ->default(0)
                                        ->afterStateUpdated(function ($set, $get) {
                                            $particulars = collect($this->other_expenses);

                                            $filtered = $particulars->filter(function ($item) {
                                                return !is_null($item['amount']) && $item['amount'] !== "";
                                            });

                                            if ($filtered->isNotEmpty()) {

                                                $sum = $filtered->sum('amount');
                                            } else {
                                                $sum = 0;
                                            }

                                            if ($get('amount') != 0 || $get('amount') != null) {
                                                $this->total_expense = $sum;
                                                if ($this->voucher_subtype->id == 27) {
                                                    $utility_particulars = collect($this->electricity_utility_particulars);
                                                    $total = $utility_particulars->sum('electricity_total') + $sum;
                                                } else if ($this->voucher_subtype->id == 70) {
                                                    $utility_particulars = collect($this->water_utility_particulars);
                                                    $total = $utility_particulars->sum('water_total') + $sum;
                                                } else if ($this->voucher_subtype->id == 71) {
                                                    $utility_particulars = collect($this->fuel_utility_particulars);
                                                    $total = $utility_particulars->sum('fuel_total') + $sum;
                                                } else if ($this->voucher_subtype->id == 74) {
                                                    $utility_particulars = collect($this->telephone_utility_particulars);
                                                    $total = round(($utility_particulars->sum('telephone_amount') + $sum), 2);
                                                } else if ($this->voucher_subtype->id == 75) {
                                                    $utility_particulars = collect($this->internet_utility_particulars);
                                                    $total = round(($utility_particulars->sum('internet_amount') + $sum), 2);
                                                }
                                                $set('../../disbursement_voucher_particulars', [
                                                    [
                                                        'purpose' => $get('../../purpose'),
                                                        'responsibility_center' => '',
                                                        'mfo_pap' => '',
                                                        'amount' => round($total, 2),
                                                    ],
                                                ]);
                                            }
                                        }),
                                ])->createItemButtonLabel('Add New Row')->visible(fn ($get) => in_array($this->voucher_subtype->id, [27, 70, 71, 74, 75])),
                            //Electricity, Water, Fuel (end)
                            #endregion
                            #region DV PARTICULARS
                            Repeater::make('disbursement_voucher_particulars')
                                ->schema([
                                    Textarea::make('purpose')
                                        ->required(),
                                    Grid::make(3)->schema([
                                        TextInput::make('responsibility_center'),
                                        TextInput::make('mfo_pap')
                                            ->label('MFO/PAP'),
                                        TextInput::make('amount')
                                            ->reactive()
                                            ->numeric()
                                            ->minValue(1)
                                            ->disabled(fn () => TravelOrder::find($this->travel_order_id)?->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS)
                                            ->required(),
                                    ]),
                                ])
                                ->minItems(1)
                                ->visible(fn ($get) => $get('travel_order_id') || !in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
                                ->disableItemDeletion(fn () => in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
                                ->disableItemCreation(fn () => in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS)),
                            #endregion
                        ]),
                        #region Actual Itinerary
                        Section::make('Actual Itinerary')
                            ->visible(fn () => $this->shouldItineraryBeVisible())
                            ->schema([
                                Card::make([
                                    Placeholder::make('travel_order_details')
                                        ->content(fn () => view('components.travel_orders.travel-order-details', [
                                            'travel_order' => TravelOrder::find($this->travel_order_id),
                                            'itinerary_entries' => $this->itinerary_entries ?? [],
                                        ])),
                                ]),
                                Builder::make('itinerary_entries')
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
                        #endregion
                    ]),
                ...$this->itinerarySection(),
                ...$this->ctcSection(),
                #region Related Documents
                Step::make('Review Related Documents')
                    ->description('Ensure all the required documents are complete before proceeding.')
                    ->schema([
                        Card::make()
                            ->schema([
                                Placeholder::make('related_documents_list')->disableLabel()->content(fn () => view('components.disbursement_vouchers.related_documents', [
                                    'voucher_subtype' => $this->voucher_subtype,
                                ])),
                            ]),
                    ]),
                #endregion

                #region Signatory
                Step::make('DV Signatory')
                    ->description('Select the appropriate signatory for the disbursement voucher.')
                    ->schema([
                        Card::make()
                            ->schema([
                                Select::make('signatory_id')
                                    ->label('Signatory')
                                    ->searchable()
                                    ->required()
                                    ->options(EmployeeInformation::pluck('full_name', 'user_id')),
                            ]),
                    ]),
                #endregion

                #region Preview
                Step::make('Preview DV')
                    ->description('Review and confirm information for submission.')
                    ->schema([
                        Card::make()
                            ->schema([
                                ViewField::make('voucher_preview')->label('Voucher Preview')->view('components.forms.voucher-preview'),
                            ]),
                    ]),
                #endregion
            ])->submitAction(new HtmlString(view('components.forms.save-button')->render())),
        ];
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        if (in_array($this->voucher_subtype->id, [6, 7]) && TravelOrder::find($this->travel_order_id)?->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
            $coverage = [];
            foreach ($this->itinerary_entries as $entry) {
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
                'travel_order_id' => $this->travel_order_id,
                'coverage' => $coverage,
            ]);

            foreach ($this->itinerary_entries as $itinerary_entry) {
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

        if ($this->voucher_subtype->id == 27) {
            $other_details = [
                'type' => 'Electricity',
                'details' => collect($this->electricity_utility_particulars)->values()->toArray(),
                'other_expenses' => collect($this->other_expenses)->values()->toArray(),
            ];
        } else if ($this->voucher_subtype->id == 70) {
            $other_details = [
                'type' => 'Water',
                'details' => collect($this->water_utility_particulars)->values()->toArray(),
                'other_expenses' => collect($this->other_expenses)->values()->toArray(),
            ];
        } else  if ($this->voucher_subtype->id == 71) {
            $other_details = [
                'type' => 'Fuel',
                'details' => collect($this->fuel_utility_particulars)->values()->toArray(),
                'other_expenses' => collect($this->other_expenses)->values()->toArray(),
            ];
        } else {
            $other_details = [];
        }
        $dv = DisbursementVoucher::create([
            'voucher_subtype_id' => $this->voucher_subtype->id,
            'user_id' => auth()->id(),
            'signatory_id' => $this->signatory_id,
            'mop_id' => filled($this->mop_id) ? $this->mop_id : null,
            'payee' => $this->payee,
            'travel_order_id' => $this->travel_order_id,
            'tracking_number' => $this->tracking_number,
            'submitted_at' => now(),
            'other_details' => $other_details,
            'current_step_id' => 3000,
            'previous_step_id' => 2000,
        ]);

        foreach ($this->disbursement_voucher_particulars as $key => $particulars) {
            $dv->disbursement_voucher_particulars()->create([
                'purpose' => $particulars['purpose'],
                'responsibility_center' => $particulars['responsibility_center'],
                'mfo_pap' => $particulars['mfo_pap'],
                'amount' => $particulars['amount'],
            ]);
        }
        if (in_array($this->voucher_subtype->id, [6, 7])) {
            TravelCompletedCertificate::create([
                'user_id' => auth()->id(),
                'signatory_id' => TravelOrder::find($this->travel_order_id)?->signatories()->first()?->id,
                'travel_order_id' => $this->travel_order_id,
                'itinerary_id' => $itinerary->id,
                'disbursement_voucher_id' => $dv->id,
                'condition' => $this->condition,
                'explanation' => $this->explanation,
            ]);
        }
        $dv->activity_logs()->create([
            'description' => $dv->current_step->process . ' ' . $dv->signatory->employee_information->full_name . ' ' . $dv->current_step->sender,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Disbursement voucher request has been submitted.')->success()->send();

        return redirect()->route('requisitioner.disbursement-vouchers.index');
    }
    private function ctcSection()
    {
        if (!in_array($this->voucher_subtype->id, [6, 7]))
            return [];
        else
            return [
                Step::make('Certificate of Travel Completed')
                    ->schema([
                        Card::make([
                            Radio::make('condition')->options([
                                '1' => 'Strictly in accordance with the approved itinerary.',
                                '2' => 'Cut short as explained below.',
                                '3' => 'Extended as explained below, additional itinerary was submitted',
                                '4' => 'Other deviation as explained below.',
                            ])
                                ->default('1'),
                            Textarea::make('explanation')->placeholder('Explanation or justifications')
                                ->required(fn ($get) => $get('condition') != 1),
                        ])
                    ]),
                Step::make('Print Certificate of Travel Completed')
                    ->schema([
                        Placeholder::make('ctc')
                            ->disableLabel()
                            ->content(function ($get) {
                                $travel_order = TravelOrder::find($this->travel_order_id);
                                $supervisor = $travel_order?->signatories()->first()?->employee_information?->full_name;
                                return view('components.forms.ctc-preview', [
                                    'condition' => $get('condition'),
                                    'explanation' => $get('explanation'),
                                    'employee' => auth()->user()->employee_information->full_name,
                                    'travel_order' => $travel_order,
                                    'supervisor' => $supervisor,
                                    'ctc' => TravelCompletedCertificate::make([
                                        'created_at' => today(),
                                    ])
                                ]);
                            }),
                    ]),
            ];
    }

    private function itinerarySection()
    {
        if (!in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
            return [];
        else
            return [
                Step::make(in_array($this->voucher_subtype->id, [6, 7]) ? 'Print Actual Itinerary' : 'Print Itinerary')
                    ->schema([
                        Card::make()
                            ->schema([
                                Placeholder::make('actual_itinerary')
                                    ->content(
                                        function () {
                                            if (TravelOrder::find($this->travel_order_id)?->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                                                $coverage = [];
                                                $to = TravelOrder::find($this->travel_order_id);
                                                foreach ($this->itinerary_entries as $entry) {
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
                                                    'travel_order_id' => $this->travel_order_id,
                                                    'coverage' => $coverage,
                                                ]);
                                                $itinerary_entries = [];
                                                foreach ($this->itinerary_entries as $itinerary_entry) {
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
                                                    'travel_order' => $to,
                                                    'immediate_signatory' => $to?->signatories()->with('employee_information')->first(),
                                                ]);
                                            }
                                            return new HtmlString('Itinerary not required by this disbursement voucher.');
                                        }
                                    )->disableLabel()
                            ])
                    ])
            ];
    }

    public function mount()
    {
        $this->tracking_number = DisbursementVoucher::generateTrackingNumber();
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-create');
    }

    private function shouldItineraryBeVisible()
    {
        return TravelOrder::find($this->travel_order_id)?->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS && in_array($this->voucher_subtype->id, [6, 7]);
    }

    private function generateItineraryEntries($old_itinerary, $travel_order)
    {
        $entries = [];
        $itinerary_entries = $old_itinerary->itinerary_entries;
        $original_per_diem = $travel_order->philippine_region?->dte->amount ?? 0;
        foreach ($old_itinerary->coverage as $key => $coverage) {
            if ($travel_order->date_to == Carbon::make($coverage['date'])) {
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
        $this->itinerary_entries = $entries;
    }
}
