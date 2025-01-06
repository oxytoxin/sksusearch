<?php

namespace App\Http\Livewire\WFP;

use DB;
use App\Models\Wfp;
use Livewire\Component;
use WireUi\Traits\Actions;
use Faker\Provider\ar_EG\Text;
use App\Models\WfpRequestedSupply;
use App\Models\WfpRequestTimeline;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class RequestSupply extends Component implements HasForms
{
    use InteractsWithForms;
    use Actions;

    public $data;

    public function mount()
    {
       $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                Grid::make(1)
                ->schema([
                    Textarea::make('particulars')
                    ->label('Particulars (Description & Specifications)')
                    ->required(),
                //     RichEditor::make('particulars')
                //     ->required()
                //     ->toolbarButtons([
                //         'bold',
                //         'bulletList',
                //         'edit',
                //         'italic',
                //         'orderedList',
                //         'preview',
                //     ])
                 ]),
                Grid::make(3)
                ->schema([
                    TextInput::make('specification')->label('Short Description')->required(),
                    TextInput::make('uom')
                    ->label('UOM')
                    ->required(),
                    TextInput::make('unit_cost')
                      ->label('Unit Cost')
                      ->numeric()
                      ->required(fn ($get) => $get('is_ppmp') == 1),
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
            'description' => 'Save the request?',
            'acceptLabel' => 'Yes, save it',
            'method'      => 'saveRequestSupply',
            'params'      => 'Saved',
        ]);
    }

    public function saveRequestSupply()
    {
        // $this->validate();
        DB::beginTransaction();
        $request = WfpRequestedSupply::create([
            'user_id' => auth()->id(),
            'particulars' => $this->data['particulars'],
            'specification' => $this->data['specification'],
            'uom' => $this->data['uom'],
            'unit_cost' => $this->data['unit_cost'],
            'is_ppmp' => $this->data['is_ppmp'],
        ]);

        WfpRequestTimeline::create([
            'wfp_request_id' => $request->id,
            'user_id' => auth()->id(),
            'activity' => 'Pending',
            'remarks' => 'Pending',
        ]);

        //forward to supply
        $request->status = 'Forwarded to Supply';
        $request->save();

        WfpRequestTimeline::create([
            'wfp_request_id' => $request->id,
            'user_id' => auth()->id(),
            'activity' => 'Forwarded to Supply',
            'remarks' => 'Forwarded to Supply',
        ]);
        DB::commit();

        $this->dialog()->success(
            $title = 'Operation Successful',
            $description = 'WFP request has been successfully created',
        );

        return redirect()->route('wfp.request-supply-list');
    }

    public function render()
    {
        return view('livewire.w-f-p.request-supply');
    }
}
