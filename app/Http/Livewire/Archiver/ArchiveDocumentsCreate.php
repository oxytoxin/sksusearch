<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ArchiveDocumentsCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $disbursement_voucher_id;

    public $journal_date;

    public $attachment;

    protected function getFormSchema()
    {
        return [
            Grid::make(4)->schema([            
            Select::make('disbursement_voucher_id')
                ->columnSpan(4)
                ->label('Disbursement Voucher')
                ->searchable()
                ->preload()
                ->options(DisbursementVoucher::all()->pluck('tracking_number', 'id'))
                ->reactive()
                ->afterStateUpdated(function ($set, $state) {
                    $dv = DisbursementVoucher::find($state);
                    $set('payee', $dv->payee);
                })
                ->required(),
            TextInput::make('document_code')
                ->columnSpan(2)
                ->label('Document Code')
                ->disabled()
                ->required(),
            TextInput::make('payee')
                ->columnSpan(2)
                ->label('Payee')
                ->disabled()
                ->required(),
           
            TextInput::make('dv_number')
                ->columnSpan(2)
                ->label('DV Number')
                ->required(),
            TextInput::make('cheque_number')
                ->label('ADA/Cheque Number')
                ->disabled()
                ->required(),
            Flatpickr::make('journal_date')
                ->label('Journal Date')
                ->disableTime()
                ->required(),
                Textarea::make('particular')
                ->columnSpan(4)
                ->label('Particular')
                ->disabled()
                ->required(),
            FileUpload::make('attachment')
                ->columnSpan(4)
            ])
        ];
    }

    public function render()
    {
        return view('livewire.archiver.archive-documents-create');
    }
}
