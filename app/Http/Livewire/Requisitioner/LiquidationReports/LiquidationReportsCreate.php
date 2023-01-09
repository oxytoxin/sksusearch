<?php

namespace App\Http\Livewire\Requisitioner\LiquidationReports;

use App\Forms\Components\SlimRepeater;
use Livewire\Component;
use App\Models\VoucherType;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use App\Models\LiquidationReport;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

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
                                        ->doesntHave('liquidation_report', 'and', function (Builder $query) {
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
                                    $this->disbursement_voucher = DisbursementVoucher::withSum('disbursement_voucher_particulars as total_amount', 'final_amount')->find($state);
                                    if ($this->disbursement_voucher) {
                                        $set('signatory_id', $this->disbursement_voucher->signatory_id);
                                        $particulars = collect();
                                        foreach ($this->disbursement_voucher->disbursement_voucher_particulars as $key => $particular) {
                                            $particulars->push([
                                                'purpose' => $particular->purpose,
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
                                TextInput::make('amount')->numeric()->minValue(0)->required()->reactive()->afterStateUpdated(function ($set, $get) {
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
                            ->columns(2),
                    ]),
                Step::make('Refund/Reimbursement')
                    ->schema([
                        Placeholder::make('gross_amount')
                            ->view('components.liquidation_reports.liquidation-details')
                            ->visible(fn ($get) => $get('disbursement_voucher_id')),
                        Fieldset::make('Refund')
                            ->schema([
                                SlimRepeater::make('refund_particulars')->schema([
                                    TextInput::make('or_number')->required()->validationAttribute('OR Number')->label('OR Number')->disableLabel(),
                                    TextInput::make('amount')->required()->validationAttribute('Amount')->numeric()->label('Amount')->disableLabel(),
                                    DatePicker::make('date')->required()->validationAttribute('Date')->withoutTime()->label('OR Date')->disableLabel(),
                                ])->columns(3)
                            ])
                            ->columns(1)
                            ->visible(function ($get) {
                                try {
                                    $particulars = collect($this->data['particulars']);
                                    return $this->disbursement_voucher && $particulars->sum('amount') < $this->disbursement_voucher->total_amount;
                                } catch (\Throwable $th) {
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
                                    $particulars = collect($this->data['particulars']);
                                    return $this->disbursement_voucher && $particulars->sum('amount') > $this->disbursement_voucher->total_amount;
                                } catch (\Throwable $th) {
                                }
                            }),
                    ]),
                Step::make('Preview')
                    ->schema([
                        Placeholder::make('preview')
                            ->view('components.liquidation_reports.liquidation-report-preview')
                    ])
            ])->submitAction(view('components.forms.save-button'))
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.requisitioner.liquidation-reports.liquidation-reports-create');
    }

    public function save()
    {
        $this->form->validate();
        DB::beginTransaction();
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
}
