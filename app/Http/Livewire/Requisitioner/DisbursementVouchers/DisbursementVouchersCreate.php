<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Models\ActivityLogType;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use App\Models\Mop;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use App\Models\VoucherSubType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
                                ->visible(fn () => in_array($this->voucher_subtype->id, [1, 2, 6, 7]))
                                ->required(fn () => in_array($this->voucher_subtype->id, [1, 2, 6, 7]))
                                ->options(TravelOrder::approved()
                                    ->whereHas('itineraries', function ($query) {
                                        $query->whereUserId(auth()->id());
                                    })
                                    ->where('travel_order_type_id', TravelOrderType::OFFICIAL_BUSINESS)
                                    ->pluck('tracking_code', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($set, $state) {
                                    $to = TravelOrder::find($state);
                                    if ($to) {
                                        $itinerary = $to->itineraries()->whereUserId(auth()->id())->first();
                                        $amount = $to->registration_amount;
                                        foreach ($itinerary['coverage'] as $entry) {
                                            $amount += $entry['per_diem'];
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
                                        $set('disbursement_voucher_particulars', []);
                                    }
                                }),
                            Radio::make('payee_mode')
                                ->label('Payee Mode')
                                ->options([
                                    'self' => 'Self',
                                    'others' => 'Others',
                                ])
                                ->visible(fn () => ! in_array($this->voucher_subtype->voucher_type_id, [1, 2]))
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
                                    ->options(Mop::pluck('name', 'id'))
                                    ->required(),
                            ]),
                            Repeater::make('disbursement_voucher_particulars')
                                ->schema([
                                    TextInput::make('purpose')->required(),
                                    Grid::make(3)->schema([
                                        TextInput::make('responsibility_center')
                                            ->required(),
                                        TextInput::make('mfo_pap')
                                            ->label('MFO/PAP')
                                            ->required(),
                                        TextInput::make('amount')
                                            ->numeric()
                                            ->required(),
                                    ]),
                                ])
                                ->minItems(1)
                                ->visible(fn ($get) => $get('travel_order_id') || ! in_array($this->voucher_subtype->id, [1, 2, 6, 7]))
                                ->disableItemDeletion(fn () => in_array($this->voucher_subtype->id, [1, 2, 6, 7]))
                                ->disableItemCreation(fn () => in_array($this->voucher_subtype->id, [1, 2, 6, 7])),
                        ]),
                    ]),
                Step::make('Review Related Documents')
                    ->description('Ensure all the required documents are complete before proceeding.')
                    ->schema([
                        Card::make()
                            ->schema([
                                ViewField::make('related_documents_list')->disableLabel()->view('components.disbursement_vouchers.related_documents'),
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
                                    ->options(EmployeeInformation::whereIn('position_id', [5, 12, 13, 11, 14, 15, 16, 17, 18, 19, 20, 21, 25])->pluck('full_name', 'user_id')),
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
            ])->submitAction(view('components.forms.save-voucher')),
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
            'current_step_id' => 1000,
            'previous_step_id' => 1000,
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
            'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
            'description' => $dv->current_step->process.' '.$dv->signatory->employee_information->full_name.' '.$dv->current_step->sender,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Disbursement voucher request has been submitted.')->success()->send();

        return redirect('/');
    }

    public function mount()
    {
        $this->tracking_number = 'DV_'.now()->format('Y').'-'.now()->format('m').'-'.rand(1, 999);
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-create');
    }
}
