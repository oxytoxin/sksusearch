<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Models\FundCluster;
use App\Models\LegacyDocument;
use DB;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;

class ArchiveLegacyDocumentsCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $fund_cluster;

    public $document_code;

    public $journal_date;

    public $particular;

    public $attachment;

    protected function getFormSchema(): array
    {
        return[
            
                Section::make("Document Details")
                ->schema([
                   Grid::make(4)
                   ->schema([

                    Select::make("fund_cluster")
                    ->required()
                    ->preload()
                    ->options(FundCluster::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        $code = FundCluster::find($state);
                        $set('document_code', $code->name.'-00-00-0000');
    
                    })
                    ->columnSpan(1),

                    TextInput::make("document_code")
                    ->label("Document Code")
                    ->columnSpan(3)
                    ->required()
                    ->mask(fn (TextInput\Mask $mask) => $mask->pattern('000-00-00-0000'))
                    ->placeholder('000-00-00-0000'),

                    TextInput::make("payee")
                    ->label("Payee name")
                    ->placeholder("Full name of requisitioner/payee")
                    ->required()
                    ->columnSpan(4),
                    
                    TextInput::make("cheque_no")
                    ->label("ADA / CHEQUE NO")
                    ->placeholder("0000000")
                    ->required()
                    ->columnSpan(2),

                    Flatpickr::make('journal_date')
                    ->label('Journal Date')
                    ->disableTime()
                    ->required()
                    ->columnSpan(2),

                    Textarea::make('particular')
                    ->columnSpan(4)
                    ->label('Particular')
                    ->required(),

                    TextInput::make("dv_number")
                    ->label("DV Number")
                    ->placeholder("")
                    ->required()
                    ->columnSpan(1),

                    FileUpload::make('attachment')
                    ->enableOpen()
                    ->multiple()
                    ->preserveFilenames()
                    ->acceptedFileTypes(['application/pdf'])
                    ->columnSpan(3)
                    
                    
                    
                   ])
                ])

        ];
    }

    public function save()
    {
        $this->validate();
        DB::beginTransction();

        $ldc = LegacyDocument::create([
            'dv_number' => $this->dv_number,
            'document_code' => $this->document_code,
            'payee_name' => $this->payee,
            'particulars' => $this->particular,
            'journal_date' => $this->journal_date,
            'upload_date' => now()->format('Y-m-d'),
            'fund_cluster_id' => $this->fund_cluster,
        ]);

        //save Files from fileupload

        DB::commit();

    
    
    }

    public function render()
    {
        return view('livewire.archiver.archive-legacy-documents-create');
    }
}
