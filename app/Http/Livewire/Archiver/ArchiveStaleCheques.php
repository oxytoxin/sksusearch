<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Models\ArchivedCheque;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ArchiveStaleCheques extends Component implements  HasForms
{
    use InteractsWithForms;

    public $cheque_number;
    public $cheque_amount;
    public $cheque_date;
    public $cheque_state;
    public $payee;

    protected function getFormSchema(): array
    {
        return [
            Section::make('Cheque Details')->schema(
                [
                    Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 4,])
                    ->schema(
                        [

                            TextInput::make("cheque_no")
                            ->label("ADA / CHEQUE NO")
                            ->placeholder("0000000")
                            ->required()
                            ->columnSpan(1),

                            TextInput::make("payee")
                            ->label("Payee name")
                            ->placeholder("Full name of payee")
                            ->required()
                            ->columnSpan(1),

                            TextInput::make("cheque_amt")
                            ->label("Cheque Amount")
                            ->placeholder("00000.00")
                            ->numeric()
                            ->required()
                            ->visible()
                            ->columnSpan(1),

                            Flatpickr::make('cheque_date')
                            ->label('Cheque Date') 
                            ->disableTime()
                            ->required()
                            ->visible()
                            ->columnSpan(1),

                            Select::make("cheque_state")
                            ->label('Cheque State') 
                            ->required()
                            ->preload()
                            ->options([
                            '1' => 'Cancelled',
                            '2' => 'Stale',
                            ])
                            ->columnSpan(
                                [
                                'default' => 1,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                ]
                            ),
                            
                            FileUpload::make('attachment')
                            ->enableOpen()
                            ->required()
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->reactive()
                            ->disk('scanned_documents')
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,])  
                        ]
                    )
                ]
            ),
        ];
    }
    public function mount()
    {
      
        $this->form->fill();
    
    }
    
    public function save()
    {
        $this->validate();
        DB::beginTransaction();

        $archiveCheque = ArchivedCheque::create([
            'cheque_number' => $this->cheque_no,
            'cheque_amount' => $this->cheque_amt,
            'cheque_date' => $this->cheque_date,
            'cheque_state' => $this->cheque_state,
            'payee' => $this->payee,
            'other_details'=>json_encode(''),
        ]);

        //save Files from fileupload
        foreach($this->attachment as $document){            
            $archiveCheque->scanned_documents()->create(
                [
                    "path"=>$document->storeAs('scanned_documents',now()->format("HismdY-").$document->getClientOriginalName()),
                    "document_name"=>$document->getClientOriginalName(),

                ]
            );
            Notification::make()->title('Upload Success')->body('Upload of '.$document->getClientOriginalName().' successful')->success()->send();
        }

        DB::commit();
        Notification::make()->title('Operation Success')->body('Cheque archived successfully')->success()->send();
        return redirect()->route('archiver.archive-cheques.create');
    }
    public function render()
    {
        return view('livewire.archiver.archive-stale-cheques');
    }

}
