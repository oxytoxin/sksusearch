<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Models\VoucherSubType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class DisbursementVouchersCreate extends Component implements HasForms
{
    use InteractsWithForms;

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
                    ]),
                Step::make('Review Related Documents')
                    ->description('Ensure all the required documents are complete before proceeding.')
                    ->schema([
                        // ...
                    ]),
                Step::make('DV Signatories')
                    ->description('Select the appropriate signatory for the disbursement voucher.')
                    ->schema([
                        // ...
                    ]),
                Step::make('Preview DV')
                    ->description('Review and confirm information for submission.')
                    ->schema([
                        // ...
                    ]),
            ]),
        ];
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-create');
    }
}
