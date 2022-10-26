<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use App\Models\LegacyDocument;
use Closure;
use DB;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
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
                ->options(DisbursementVoucher::where('current_step_id','22000')->pluck('tracking_number', 'id'))
                ->reactive()
                ->afterStateUpdated(function ($set, $state) {
                    $dv = DisbursementVoucher::find($state);
                    $set('payee', $dv->payee);
                    $set('document_code', $dv->tracking_number);
                    $set('particular', $dv->disbursement_voucher_particulars[0]['purpose']);
                    $set('dv_number', $dv->dv_number);
                    $set('cheque_number', $dv->cheque_number);
                    $set('journal_date', $dv->journal_date);

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
                ->disabled()
                ->required(),
            TextInput::make('cheque_number')
                ->label('ADA/Cheque Number')
                ->disabled()
                ->required(),
            Flatpickr::make('journal_date')
                ->label('Journal Date')
                ->disableTime()
                ->disabled()
                ->required(),
                Textarea::make('particular')
                ->columnSpan(4)
                ->label('Particular')
                ->disabled()
                ->required(),
            FileUpload::make('attachment')
                ->multiple()
                ->acceptedFileTypes(['application/pdf'])
                ->enableOpen()
                ->enableReordering()
                ->columnSpan(4)
            ])
        ];
    }

    public function save()
    {
        
        $this->validate();
        DB::beginTransaction();
        $dv = DisbursementVoucher::findOrFail($this->disbursement_voucher_id);
        foreach($this->attachment as $document){            
            $dv->scanned_documents()->create(
                [
                    "path"=>$document->storeAs('scanned_documents',now()->format("HismdY-").$document->getClientOriginalName()),
                    "document_name"=>$document->getClientOriginalName(),

                ]
            );
            Notification::make()->title('Upload Success')->body('Upload of '.$document->getClientOriginalName().' successful')->success()->send();
        }
        $dv->update(
            [
            'current_step_id' => "23000",
            'previous_step_id' => "22000",
            ]
            );
        $dv->activity_logs()->create([
                'description' => $dv->current_step->process . ' SKSU document archives '. $dv->current_step->sender . ', ' . auth()->user()->employee_information->full_name ,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Documents have been archived successfully')->success()->send();

        return redirect()->route('archiver.archive-doc.create');
    
    }

    public function render()
    {
        return view('livewire.archiver.archive-documents-create');
    }
}
