<?php

namespace App\Http\Livewire\WFP;

use App\Models\WfpRequestedSupply;
use Livewire\Component;
use WireUi\Traits\Actions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class RequestSupplyEdit extends Component implements HasForms
{
    use InteractsWithForms;
    use Actions;

    public $data;
    public $record;

    public function mount($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->form->fill([
            'particulars' => $this->record->particulars,
            'specification' => $this->record->specification,
            'uom' => $this->record->uom,
            'unit_cost' => $this->record->unit_cost,
            'is_ppmp' => $this->record->is_ppmp,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                Grid::make(3)
                ->schema([
                    TextInput::make('particulars')->label('Particular')->required(),
                    TextInput::make('uom')
                    ->label('UOM')
                    ->required(),
                    TextInput::make('unit_cost')
                      ->label('Unit Cost')
                      ->numeric()
                      ->required(fn ($get) => $get('is_ppmp') == 1),
                ]),
                Grid::make(1)
                ->schema([
                    RichEditor::make('specification')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'edit',
                        'italic',
                        'orderedList',
                        'preview',
                    ])
                    // TextInput::make('specification')
                    // ->required(),
                ]),
                Radio::make('is_ppmp')
                ->required()
                ->reactive()
                ->label('Is this PPMP?')
                ->boolean()
                ->default(true)->inline(),

            ]),

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
            'description' => 'Update the request?',
            'acceptLabel' => 'Yes, update it',
            'method'      => 'updateRequestSupply',
            'params'      => 'Updated',
        ]);
    }

    public function updateRequestSupply()
    {
        $this->validate();

        $this->record->update([
            'particulars' => $this->data['particulars'],
            'specification' => $this->data['specification'],
            'uom' => $this->data['uom'],
            'unit_cost' => $this->data['unit_cost'],
            'is_ppmp' => $this->data['is_ppmp'],
        ]);

        $this->dialog()->success(
            $title = 'Operation Successful',
            $description = 'WFP request has been successfully updated',
        );

        return redirect()->route('wfp.request-supply-list');
    }

    public function render()
    {
        return view('livewire.w-f-p.request-supply-edit');
    }
}
