<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Models\FundCluster;
use App\Models\LegacyDocument;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;

class ArchiveLegacyDocumentsCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $fund_cluster;

    public $document_code;

    public $document_category;

    public $cheque_no;

    public $cheque_amt;

    public $cheque_date;

    public $journal_date;

    public $particulars =[];

    public $attachment;

    public $dv_number;

    public $payee;

    public $cheque_state;

    protected function getFormSchema(): array
    {
        return[
                Section::make("Document Details")
                ->schema([
                   Grid::make(4)
                   ->schema([
                    Select::make("document_category")
                    ->required()
                    ->preload()
                    ->default(1)
                    ->options(
                    ['1' => 'Disbursement Voucher',
                    '2' => 'Liquidation Report',
                    ])
                    ->reactive()
                    ->columnSpan(1),

                    Select::make("fund_cluster")
                    ->required()
                    ->preload()
                    ->options(FundCluster::all()->pluck('name', 'id'))
                    ->reactive()
                    ->required(fn () => in_array($this->document_category,['1','2']))
                    ->visible(fn () => in_array($this->document_category,['1','2']))
                    ->afterStateUpdated(function ($set, $state) {
                        $code = FundCluster::find($state);
                        $set('document_code', $code->name.'-00-00-0000');

                    })
                    ->columnSpan(1),

                    TextInput::make("document_code")
                    ->label("Document Code")
                    ->columnSpan(2)
                    ->mask(fn (TextInput\Mask $mask) => $mask->pattern('000-00-00-0000****************'))
                    ->unique('App\Models\LegacyDocument')
                    ->required(fn () => in_array($this->document_category,['1','2']))
                    ->visible(fn () => in_array($this->document_category,['1','2']))
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
                    ->columnSpan(1),

                    TextInput::make("cheque_amt")
                    ->label("Cheque Amount")
                    ->placeholder("00000.00")
                    ->mask(fn (TextInput\Mask $mask) => $mask
                    ->numeric()

                    ->thousandsSeparator(','), // Add a separator for thousands.
                )
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
                    '1' => 'Encashed',
                    '2' => 'Cancelled',
                    '3' => 'Stale',
                    ])
                    ->reactive()
                    ->default(1)
                    ->columnSpan(1),


                    Flatpickr::make('journal_date')
                    ->label('Journal Date')
                    ->disableTime()
                    ->required(fn () => in_array($this->document_category,['1','2']))
                    ->visible(fn () => in_array($this->document_category,['1','2']))
                    ->columnSpan(1),


                    TextInput::make("dv_number")
                    ->label("Disbursement Voucher Number")
                    ->placeholder("")
                    ->required()
                    ->unique('App\Models\LegacyDocument')
                    ->required(fn () => in_array($this->document_category,['1','2']))
                    ->visible(fn () => in_array($this->document_category,['1','2']))
                    ->columnSpan(3),

                    Repeater::make('particulars')
                    ->schema([
                                Textarea::make('purpose')->required(),
                             ])
                    ->minItems(1)
                    ->required(fn () => in_array($this->document_category,['1','2']))
                    ->visible(fn () => in_array($this->document_category,['1','2']))
                    ->columnSpan(4),



                    FileUpload::make('attachment')
                    ->enableOpen()
                    ->multiple()
                    ->preserveFilenames()
                    ->acceptedFileTypes(['application/pdf'])
                    ->reactive()
                    ->disk('scanned_documents')
                    ->columnSpan(4)
                   ])
                ])

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
        $dv_particulars=[];

        foreach ($this->particulars as $particular) {
            $dv_particulars[] =[
                "purpose"=>$particular['purpose']
            ];
        }

        $ldc = LegacyDocument::create([
            'dv_number' => $this->dv_number,
            'document_code' => $this->document_code,
            'payee_name' => $this->payee,
            'particulars' => $dv_particulars,
            'other_details'=>json_encode(''),
            'journal_date' => $this->journal_date,
            'upload_date' => now(),
            'fund_cluster_id' => $this->fund_cluster,
            'cheque_number' => $this->cheque_no,
            'cheque_amount' => $this->cheque_amt,
            'cheque_date' => $this->cheque_date,
            'cheque_state' => $this->cheque_state,
            'document_category' => $this->document_category,
        ]);

        //save Files from fileupload
        foreach($this->attachment as $document){
            $ldc->scanned_documents()->create(
                [
                    "path"=>$document->storeAs('scanned_documents',now()->format("HismdY-").$document->getClientOriginalName()),
                    "document_name"=>$document->getClientOriginalName(),

                ]
            );
            Notification::make()->title('Upload Success')->body('Upload of '.$document->getClientOriginalName().' successful')->success()->send();
        }

        DB::commit();
        Notification::make()->title('Operation Success')->body('Legacy document has been archived successfully')->success()->send();

        return redirect()->route('archiver.archive-leg-doc.create');

    }

    public function render()
    {
        return view('livewire.archiver.archive-legacy-documents-create');
    }
}
