<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryItems;
use App\Models\CostCenter;
use App\Models\FundClusterWFP;
use App\Models\Supply;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class CreateWFP extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    public $data;
    public $total_quantity;
    public $costCenter;

    public function mount()
    {
        $this->form->fill();
        $this->costCenter = CostCenter::where('office_id', auth()->user()->employee_information->office_id)->first();
        $this->data['cost_center_head'] = $this->costCenter->office->head_employee?->full_name;
        $this->data['cost_center'] = $this->costCenter->name.' - '.$this->costCenter->office->name;
        $this->total_quantity = 0;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\Select::make('fund')
                ->options(FundClusterWFP::all()->pluck('name', 'id'))->required(),
                Forms\Components\TextInput::make('fund_description')->required(),
                Forms\Components\Select::make('source_fund')
                ->label('Source of Fund')
                ->reactive()
                ->afterStateUpdated(fn ($state, $set) => $state != 'MISCELLANEOUS/FIDUCIARY FEE' ? $set('specify_fund_source', '') : '')
                ->options([
                    'TUITION FEE - RESEARCH FUND' => 'TUITION FEE - RESEARCH FUND',
                    'TUITION FEE - EXTENSION FUND' => 'TUITION FEE - EXTENSION FUND',
                    'TUITION FEE - STUDENT DEVELOPMENT' => 'TUITION FEE - STUDENT DEVELOPMENT',
                    'TUITION FEE - FACILITIES DEVELOPMENT' => 'TUITION FEE - FACILITIES DEVELOPMENT',
                    'TUITION FEE - CURRICULUM DEVELOPMENT' => 'TUITION FEE - CURRICULUM DEVELOPMENT',
                    'MISCELLANEOUS/FIDUCIARY FEE' => 'MISCELLANEOUS/FIDUCIARY FEE ',
                ])->required(),
                Forms\Components\TextInput::make('specify_fund_source')
                ->label('if miscellaneous/fiduciary fee, please specify')
                ->required()
                ->visible(fn ($get) => $get('source_fund') === 'MISCELLANEOUS/FIDUCIARY FEE'),
                Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('cost_center')->disabled()->required(),
                    Forms\Components\TextInput::make('cost_center_head')->disabled()->required(),
                ]),
                Forms\Components\Grid::make(1)
                ->schema([
                    Forms\Components\TextInput::make('specific_fund_source')->required(),
                ]),
                Fieldset::make('meta')
                ->label('')
                ->schema([
                    Repeater::make('members')
                    ->label('')
                    ->schema([
                        Forms\Components\TextInput::make('description')->columnSpanFull()->required(),
                        Repeater::make('test')->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(5)
                            ->schema([
                                Forms\Components\TextInput::make('uacs_code')->disabled()->required(),
                                Forms\Components\Select::make('account_title')
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set){
                                    $uacs_code = CategoryItems::find($state)->uacs_code;
                                    $set('uacs_code', $uacs_code);
                                })
                                ->options(fn () => CategoryItems::all()->pluck('name', 'id'))
                                ->required(),
                                Forms\Components\Select::make('particulars')
                                ->reactive()
                                ->options(fn ($get) => Supply::where('category_item_id', $get('account_title'))->pluck('particulars', 'id'))
                                ->required(),
                                Forms\Components\TextInput::make('title_of_program')->required(),
                                Forms\Components\TextInput::make('budgetary_requirement')->required(),
                            ]),
                            Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('quantity')->numeric()->default(0)->required(),
                                Forms\Components\TextInput::make('unit_of_measurement')->required(),
                                Forms\Components\TextInput::make('cost_per_unit')->required(),
                                Forms\Components\TextInput::make('estimated_budget')->required(),
                            ]),
                            Forms\Components\Grid::make(1)
                            ->schema([
                                TableRepeater::make('quantity_year')
                                ->label('')
                                ->schema([
                                    Forms\Components\TextInput::make('jan')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                    foreach ($this->data['members'] as $items) {
                                        foreach ($items['test'] as $second_entry) {
                                            foreach ($second_entry['quantity_year'] as $entry) {
                                                $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                            }
                                        }
                                    }
                                     $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('feb')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('mar')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('apr')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('may')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('jun')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('jul')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('aug')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('sep')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('oct')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('nov')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }
                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                    Forms\Components\TextInput::make('dec')->numeric()->default(0)
                                    ->reactive()
                                     ->afterStateUpdated(function ($state, $set){
                                        foreach ($this->data['members'] as $items) {
                                            foreach ($items['test'] as $second_entry) {
                                                foreach ($second_entry['quantity_year'] as $entry) {
                                                    $this->total_quantity = array_sum(array_slice($entry, 0, 12));
                                                }
                                            }
                                        }

                                         $set('../../quantity', $this->total_quantity);
                                })
                                    ->required(),
                                ])
                                ->reactive()

                                ->hideLabels()
                                ->disableItemCreation()
                                ->disableItemDeletion()
                                ->disableItemMovement()
                                ->columnSpan('full'),
                            ]),
                            ])
                    ])
                    ->columnSpanFull()
                    ->columns(2),
                ])
            ])
        ];
    }

    public function submit()
    {
        dd($this->data);
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function render(): View
    {
        return view('livewire.w-f-p.create-w-f-p');
    }
}
