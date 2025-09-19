<?php

    namespace App\Http\Livewire\Cashier\Reports;

    use App\Models\DisbursementVoucher;
    use App\Models\FundCluster;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Livewire\Component;

    class ReportOfChecksIssued extends Component implements HasForms
    {
        use InteractsWithForms;

        public int $fund_cluster_id = 1;

        protected function getFormSchema(): array
        {
            return [
                Select::make('fund_cluster_id')
                    ->options(FundCluster::pluck('name', 'id'))
                    ->reactive()
            ];
        }


        public function render()
        {
            return view('livewire.cashier.reports.report-of-checks-issued', [
                'disbursement_vouchers' => DisbursementVoucher::query()
                    ->whereNotNull('cheque_number')
                    ->where('fund_cluster_id', $this->fund_cluster_id)
                    ->withSum('disbursement_voucher_particulars', 'final_amount')
                    ->get(),
                'fund_cluster' => FundCluster::find($this->fund_cluster_id)
            ]);
        }
    }
