<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use App\Models\Mop;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use App\Models\VoucherSubType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Str;

class DisbursementVouchersCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $tracking_number;

    public $travel_order_id;

    public $disbursement_voucher_particulars = [];

    public $payee;

    public $mop_id;

    public $signatory_id;

    public VoucherSubType $voucher_subtype;

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
                                ->options(VoucherSubType::all()->pluck('name', 'id'))
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
                                    ->pluck('tracking_code', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($set, $state) {
                                    $to = TravelOrder::find($state);
                                    if ($to && $to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                                        $itinerary = $to->itineraries()->whereUserId(auth()->id())->first();

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
                                                'purpose' => '',
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
                            Repeater::make('disbursement_voucher_particulars')
                                ->schema([
                                    Textarea::make('purpose')->required(),
                                    Grid::make(3)->schema([
                                        TextInput::make('responsibility_center'),
                                        TextInput::make('mfo_pap')
                                            ->label('MFO/PAP'),
                                        TextInput::make('amount')
                                            ->numeric()
                                            ->required(),
                                    ]),
                                ])
                                ->minItems(1)
                                ->visible(fn ($get) => $get('travel_order_id') || !in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
                                ->disableItemDeletion(fn () => in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS))
                                ->disableItemCreation(fn () => in_array($this->voucher_subtype->id, VoucherSubType::TRAVELS)),
                        ]),
                    ]),
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
                Step::make('Preview DV')
                    ->description('Review and confirm information for submission.')
                    ->schema([
                        Card::make()
                            ->schema([
                                ViewField::make('voucher_preview')->label('Voucher Preview')->view('components.forms.voucher-preview'),
                            ]),
                    ]),
            ])->submitAction(view('components.forms.save-button')),
        ];
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();

        $dv = DisbursementVoucher::create([
            'voucher_subtype_id' => $this->voucher_subtype->id,
            'user_id' => auth()->id(),
            'signatory_id' => $this->signatory_id,
            'mop_id' => $this->mop_id,
            'payee' => $this->payee,
            'travel_order_id' => $this->travel_order_id,
            'tracking_number' => $this->tracking_number,
            'submitted_at' => now(),
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
        $dv->activity_logs()->create([
            'description' => $dv->current_step->process . ' ' . $dv->signatory->employee_information->full_name . ' ' . $dv->current_step->sender,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Disbursement voucher request has been submitted.')->success()->send();

        return redirect()->route('requisitioner.disbursement-vouchers.index');
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
}
