<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\Campus;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\RequestSchedule;
use App\Models\EmployeeInformation;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class FuelRequisition extends Component implements HasForms
{
    use InteractsWithForms;

    public $request;
    public $showPrintable = false;
    public $selectedDate;
    public $slip_number;
    public $supplier_id;
    public $address;
    public $article;
    public $other_article;
    public $purpose;
    public $driver_id;

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
        $this->slip_number = now()->year . '-' . str_pad(RequestSchedule::max('id') + 1, 5, '0', STR_PAD_LEFT);
    }


    protected function getFormSchema()
    {
        return [
            TextInput::make('slip_number')
                ->required()
                ->disabled(),
            Select::make('supplier_id')
                ->label('Supplier')
                ->options(Supplier::pluck('name', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($set, $state) => $set('address', Supplier::find($state)->address)),
            Textarea::make('address')
            ->required()
            ->disabled(),
            Select::make('article')
            ->label('Articles')
            ->options([
                'Gasoline' => 'Gasoline',
                'Diesel' => 'Diesel',
                'Others' => 'Others',
            ])
            ->reactive()
            ->required(),
            TextInput::make('other_article')
            ->label('Others')
            ->required(fn ($get) => $get('article') == 'Others')
            ->visible(fn ($get) => $get('article') == 'Others'),
            Grid::make(2)
            ->schema([
                TextInput::make('quantity')
                ->label('Quantity')
                ->required(),
                TextInput::make('unit')
                ->label('Unit')
                ->required(),
            ]),
            Textarea::make('purpose')
            ->required(),
            Select::make('driver_id')
            ->label('Requested By : / Driver :')
            ->options(EmployeeInformation::where('position_id', 28)
                ->whereHas('office', function ($query) {
                    return $query->where('campus_id', '=', auth()->user()->employee_information->office->campus_id);
                })->pluck('full_name', 'id'))
            ->searchable()
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.fuel-requisition');
    }
}
