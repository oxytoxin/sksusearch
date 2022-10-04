<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ArchiveDocumentsCreate extends Component implements HasForms
{
    use InteractsWithForms;

    protected function getFormSchema()
    {
        return [
            Select::make('disbursement_voucher_id')
                ->label('Disbursement Voucher')
                ->options(DisbursementVoucher::all()->pluck('tracking_number', 'id'))
                ->searchable()
                ->required(),
            TextInput::make('document_code')
                ->label('Document Code')
                ->disabled()
                ->required(),
            TextInput::make('payee')
                ->label('Payee')
                ->disabled()
                ->required(),
            TextInput::make('cheque_number')
                ->label('ADA/Cheque Number')
                ->disabled()
                ->required(),
            Textarea::make('particular')
                ->label('Particular')
                ->disabled()
                ->required(),
            Flatpickr::make('journal_date')
                ->label('Journal Date')
                ->disableTime()
                ->required(),
            TextInput::make('dv_number')
                ->label('DV Number')
                ->required(),
            FileUpload::make('attachment'),

        ];
    }

    public function render()
    {
        return view('livewire.archiver.archive-documents-create');
    }
}
