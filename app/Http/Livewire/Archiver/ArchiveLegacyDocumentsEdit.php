<?php

namespace App\Http\Livewire\Archiver;

use App\Forms\Components\Flatpickr;
use App\Mail\VerificationCode;
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
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ArchiveLegacyDocumentsEdit extends Component implements HasForms
{
    use InteractsWithForms;

    public $fund_cluster;

    public $document_code;

    public $document_category;

    public $cheque_no;

    public $cheque_amt;

    public $cheque_date;

    public $journal_date;

    public $particulars = [];

    public $attachment;

    public $dv_number;

    public $payee;

    public $cheque_state;

    public $ldc;

    public $show_Edit = false;

    public $codeValid = false;

    public $invalid = false;

    public $code = "";

    public $enteredCode;

    public $codeSent = false;


    public function updated($name, $value)
    {
        if ($name == 'enteredCode') {
            $this->checkCode();
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make("Document Details")
                ->schema([
                    Grid::make(4)
                        ->schema([
                            Select::make("document_category")
                                ->required()
                                ->preload()
                                ->default(1)
                                ->options(
                                    [
                                        '1' => 'Disbursement Voucher',
                                        '2' => 'Liquidation Report',
                                    ]
                                )
                                ->reactive()
                                ->columnSpan(1),

                            Select::make("fund_cluster")
                                ->required()
                                ->preload()
                                ->options(FundCluster::all()->pluck('name', 'id'))
                                ->reactive()
                                ->required(fn () => in_array($this->document_category, ['1', '2']))
                                ->visible(fn () => in_array($this->document_category, ['1', '2']))
                                ->afterStateUpdated(function ($set, $state) {
                                    $code = FundCluster::find($state);
                                    $set('document_code', $code->name . '-00-00-0000');
                                })
                                ->columnSpan(1),

                            TextInput::make("document_code")
                                ->label("Document Code")
                                ->columnSpan(2)
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('000-00-00-0000'))
                                // ->exists('App\Models\LegacyDocument')
                                ->required(fn () => in_array($this->document_category, ['1', '2']))
                                ->visible(fn () => in_array($this->document_category, ['1', '2']))
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
                                ->required(fn () => in_array($this->document_category, ['1', '2']))
                                ->visible(fn () => in_array($this->document_category, ['1', '2']))
                                ->columnSpan(1),


                            TextInput::make("dv_number")
                                ->label("Disbursement Voucher Number")
                                ->placeholder("")
                                ->required()
                                // ->exists('App\Models\LegacyDocument')
                                ->required(fn () => in_array($this->document_category, ['1', '2']))
                                ->visible(fn () => in_array($this->document_category, ['1', '2']))
                                ->columnSpan(3),

                            Repeater::make('particulars')
                                ->schema([
                                    Textarea::make('purpose')->required(),
                                ])
                                ->minItems(1)
                                ->required(fn () => in_array($this->document_category, ['1', '2']))
                                ->visible(fn () => in_array($this->document_category, ['1', '2']))
                                ->columnSpan(4),



                            FileUpload::make('attachment')
                                ->maxSize(50000)
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
    public function mount(LegacyDocument $legacy_document)
    {
        $this->ldc = $legacy_document;
        $this->form->fill([
            'journal_date' => $legacy_document->journal_date->format('Y-m-d'),
            'document_code' => $legacy_document->document_code,
            'fund_cluster' => $legacy_document->fund_cluster_id,
            'document_category' => $legacy_document->document_category,
            'cheque_no' => $legacy_document->cheque_number,
            'cheque_amt' => $legacy_document->cheque_amount,
            'cheque_date' => $legacy_document->cheque_date,
            'particulars' => $legacy_document->particulars,
            'dv_number' => $legacy_document->dv_number,
            'payee' => $legacy_document->payee_name,
            'cheque_state' => $legacy_document->cheque_state

        ]);
    }

    public function save()
    {
        $this->validate([
            'document_code' => 'required|unique:legacy_documents,document_code',
            'dv_number' => 'required|unique:legacy_documents,dv_number',
        ]);
        DB::beginTransaction();
        $dv_particulars = [];

        foreach ($this->particulars as $particular) {
            $dv_particulars[] = [
                "purpose" => $particular['purpose']
            ];
        }


        $this->ldc->dv_number = $this->dv_number;
        $this->ldc->document_code = $this->document_code;
        $this->ldc->payee_name = $this->payee;
        $this->ldc->particulars = $dv_particulars;
        $this->ldc->other_details = json_encode('');
        $this->ldc->journal_date = $this->journal_date;
        $this->ldc->fund_cluster_id = $this->fund_cluster;
        $this->ldc->cheque_number = $this->cheque_no;
        $this->ldc->cheque_amount = $this->cheque_amt;
        $this->ldc->cheque_date = $this->cheque_date;
        $this->ldc->cheque_state = $this->cheque_state;
        $this->ldc->document_category = $this->document_category;

        $this->ldc->save();
        foreach ($this->attachment as $document) {
            $this->ldc->scanned_documents()->create(
                [
                    "path" => $document->storeAs('scanned_documents', now()->format("HismdY-") . $document->getClientOriginalName()),
                    "document_name" => $document->getClientOriginalName(),

                ]
            );
            Notification::make()->title('Upload Success')->body('Upload of ' . $document->getClientOriginalName() . ' successful')->success()->send();
        }

        DB::commit();
        Notification::make()->title('Update Success')->body('Legacy document has been updated successfully')->success()->send();

        return redirect()->route('archiver.view-archives');
    }

    public function sendCode()
    {
        $hash_pool = "6448128edd17012e33c92c887beb336886b41fd7e50e143d96819c6cc96ef1b8576e4347ee02d4933e6be8d6bbb01287f463dc9acaaefe9409bf2934c197b3ab";
        if ($this->codeSent == false) {
            for ($i = 0; $i < 8; $i++) {

                $this->code .=  substr($hash_pool, rand(0, strlen($hash_pool) - 1), 1);
            }
        }
        Mail::to(['geraldrebamonte@sksu.edu.ph', 'sksusearch@sksu.edu.ph'])->send(new VerificationCode($this->code, $this->ldc));
        $this->codeSent = true;
        Notification::make()
            ->title('Code Sent')
            ->body('Please Contact the authorized personnel.')
            ->success()
            ->send();
    }

    public function test()
    {
    }

    public function checkCode()
    {
        if ($this->enteredCode == $this->code) {
            Notification::make()
                ->title('Success')
                ->body('Code confirmed. Editing Enabled')
                ->success()
                ->send();
            $this->show_Edit = true;
            $this->codeValid = true;
            $this->invalid = false;
        } else {
            $this->invalid = true;
            Notification::make()
                ->title('Code Invalid')
                ->success()
                ->send();
        }
    }


    public function render()
    {
        return view('livewire.archiver.archive-legacy-documents-edit');
    }
}
