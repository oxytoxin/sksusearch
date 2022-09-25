<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Models\Mop;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use App\Models\VoucherSubType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class DisbursementVouchersCreate extends Component implements HasForms
{
    use InteractsWithForms;
    public $tracking_number;
    // public $payee;

    public VoucherSubType $voucher_subtype;

    protected function getFormSchema()
    {
        return [
            Wizard::make([
                Step::make('DV Main Information Form')
                    ->description('Fill up the form for the disbursement voucher.')
                    ->schema([
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
                                ->whereHas('iteneraries', function ($query) {
                                    $query->whereUserId(auth()->id());
                                })
                                ->where('travel_order_type_id', TravelOrderType::OFFICIAL_BUSINESS)
                                ->pluck('tracking_code', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                $to = TravelOrder::find($state);
                                $itenerary = $to->iteneraries()->whereUserId(auth()->id())->first();
                                $set('disbursement_voucher_particulars', [
                                    [
                                        'purpose' => $to->purpose,
                                        'responsibility_center' => '',
                                        'mfo_pap' => '',
                                        'amount' => $to->total_amount,
                                    ],
                                ]);
                            }),
                        Radio::make('payee_mode')
                            ->label('Payee Mode')
                            ->options([
                                'self' => 'Self',
                                'others' => 'Others',
                            ])
                            ->visible(fn () => !in_array($this->voucher_subtype->id, [1, 2, 6, 7]))
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
                                ->disabled(fn ($get) => $get('payee_mode') == 'self')
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
                                TextInput::make('purpose'),
                                Grid::make(3)->schema([
                                    TextInput::make('responsibility_center')
                                        ->required()
                                        ->required(fn () => in_array($this->voucher_subtype->id,[1,2,6,7])),
                                    TextInput::make('mfo_pap')
                                        ->label('MFO/PAP')
                                        ->required()
                                        ->required(fn () => in_array($this->voucher_subtype->id,[1,2,6,7])),
                                    TextInput::make('amount')
                                        ->numeric()
                                        ->required()
                                        ->required(fn () => in_array($this->voucher_subtype->id,[1,2,6,7])),
                                ]),
                            ])
                            ->disableItemDeletion(fn () => in_array($this->voucher_subtype->id, [1, 2, 6, 7]))
                            ->disableItemCreation(fn () => in_array($this->voucher_subtype->id, [1, 2, 6, 7])),
                    ]),
                Step::make('Review Related Documents')
                    ->description('Ensure all the required documents are complete before proceeding.')
                    ->schema([
                        // ...
                    ]),
                Step::make('DV Signatories')
                    ->description('Select the appropriate signatory for the disbursement voucher.')
                    ->schema([
                        
                    ]),
                Step::make('Preview DV')
                    ->description('Review and confirm information for submission.')
                    ->schema([
                        Card::make()
                        ->schema([
                            ViewField::make('voucher_preview')->label("Voucher Preview")->view('components.forms.voucher-preview')
                        ])  
                    ]),
            ])->skippable(),
        ];
    }

    public function mount()
    {
        $this->form->fill(
            ["tracking_number"=>"DV_".now()->format('Y').'-'.now()->format('m').'-'.rand(1,999),'payee_mode'=>'self','payee'=>auth()->user()->employee_information->full_name]
        );
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-create');
    }
}
