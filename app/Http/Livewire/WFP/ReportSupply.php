<?php

namespace App\Http\Livewire\WFP;

use DB;
use App\Models\Supply;
use Livewire\Component;
use App\Models\ErrorQuery;
use WireUi\Traits\Actions;
use App\Models\ReportedSupply;
use Faker\Provider\ar_EG\Text;
use App\Models\WfpRequestedSupply;
use App\Models\WfpRequestTimeline;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ReportSupply extends Component implements HasForms
{
    use InteractsWithForms;
    use Actions;

    public $data;
    public $record;

    public function mount($record = null)
    {
        if ($record) {
            // Fetch and populate values based on $record
            $this->record = Supply::find($record);
            $this->form->fill([
                'supply_id' => $this->record->id,
                'particulars' => $this->record->particulars,
                'specification' => $this->record->specifications,
                'supply_code' => $this->record->supply_code,
                'uacs_code' => $this->record->categoryItems->uacs_code,
                'account_title' => $this->record->categoryItems->name,
                'title_group' => $this->record->categoryGroups->name,
                'uom' => $this->record->uom,
                'unit_cost' => $this->record->unit_cost,
                'is_ppmp' => $this->record->is_ppmp,
            ]);
        }
    }

    public function fillForm($id)
    {
        $this->record = Supply::find($id);
        $this->form->fill([
            'supply_id' => $this->record->id,
            'particulars' => $this->record->particulars,
            'specification' => $this->record->specifications,
            'supply_code' => $this->record->supply_code,
            'uacs_code' => $this->record->categoryItems->uacs_code,
            'account_title' => $this->record->categoryItems->name,
            'title_group' => $this->record->categoryGroups->name,
            'uom' => $this->record->uom,
            'unit_cost' => $this->record->unit_cost,
            'is_ppmp' => $this->record->is_ppmp,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(1)
            ->schema([
                Select::make('supply_id')
                ->label('Supply')
                ->options(Supply::pluck('particulars', 'id')->toArray())
                ->preload()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($record) {
                    $this->fillForm($this->data['supply_id']);
                    // $this->record = Supply::find($this->data['supply_id']);
                    // $this->data['particulars'] = $this->record->particulars;
                    // $this->data['specification'] = $this->record->specifications;
                    // $this->data['supply_code'] = $this->record->supply_code;
                    // $this->data['uacs_code'] = $this->record->categoryItems->uacs_code;
                    // $this->data['account_title'] = $this->record->categoryItems->name;
                    // $this->data['title_group'] = $this->record->categoryGroups->name;
                    // $this->data['uom'] = $this->record->uom;
                    // $this->data['unit_cost'] = $this->record->unit_cost;
                    // $this->data['is_ppmp'] = $this->record->is_ppmp;
                }),
            ]),

            Section::make('Supply Details')
            ->schema([
                Textarea::make('particulars')->disabled(),
                // RichEditor::make('particulars')
                //     ->required()
                //     ->disabled()
                //     ->toolbarButtons([
                //         'bold',
                //         'bulletList',
                //         'edit',
                //         'italic',
                //         'orderedList',
                //         'preview',
                //     ]),
                Grid::make(3)
                ->schema([
                    TextInput::make('specification')->required()->disabled(),
                    TextInput::make('supply_code')->required()->disabled(),
                    TextInput::make('uacs_code')->required()->disabled(),
                    TextInput::make('account_title')->required()->disabled(),
                    TextInput::make('title_group')->required()->disabled(),
                    TextInput::make('uom')->label('UOM')
                    ->disabled(),
                    TextInput::make('unit_cost')
                      ->label('Unit Cost')
                      ->formatStateUsing(fn ($record) => number_format($this->record->unit_cost, 2))
                      ->disabled(),

                ]),
                Radio::make('is_ppmp')
                ->boolean()
                ->inline()->disabled()
            ])
            ->collapsible()
            ->columns(1),
            Section::make('Query')
            ->schema([
                Grid::make(1)
                ->schema([
                    Select::make('error_query')
                    ->label('Error Type')
                    ->options(ErrorQuery::pluck('description', 'id')->toArray())
                    ->required(),
                    RichEditor::make('note')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'edit',
                        'italic',
                        'orderedList',
                        'preview',
                    ]),
                ]),
            ])
            ->columns(1)
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function save()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Report this supply query?',
            'acceptLabel' => 'Yes, report it',
            'method'      => 'confirmSave',
            'params'      => 'Saved',
        ]);
    }

    public function confirmSave()
    {
        $this->validate();

        DB::beginTransaction();
        $record = ReportedSupply::create([
            'user_id' => auth()->id(),
            'supply_id' => $this->data['supply_id'],
            'error_query_id' => $this->data['error_query'],
            'note' => $this->data['note'],
        ]);
        DB::commit();

        Notification::make()->title('Operation Success')->body('Reported supply query is submitted for verification.')->success()->send();
        return redirect()->route('wfp.report-supply');
    }


    public function render()
    {
        return view('livewire.w-f-p.report-supply');
    }
}
