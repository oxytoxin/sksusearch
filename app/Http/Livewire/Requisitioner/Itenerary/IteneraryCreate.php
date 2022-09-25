<?php

namespace App\Http\Livewire\Requisitioner\Itenerary;

use App\Forms\Components\Flatpickr;
use App\Models\Itenerary;
use App\Models\Mot;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class IteneraryCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $travel_order_id;

    public $itenerary_entries = [];

    public function getFormSchema()
    {
        return [
            Select::make('travel_order_id')
                ->label('Travel Order')
                ->searchable()
                ->preload()
                ->options(TravelOrder::approved()
                    ->whereDoesntHave('iteneraries', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->whereHas('applicants', function ($query) {
                        $query->whereUserId(auth()->id());
                    })
                    ->where('travel_order_type_id', TravelOrderType::OFFICIAL_BUSINESS)
                    ->pluck('tracking_code', 'id'))
                ->afterStateUpdated(function () {
                    $to = TravelOrder::with('philippine_region.dte')->find($this->travel_order_id);
                    $entries = [];
                    if (isset($to)) {
                        $days = CarbonPeriod::between($to->date_from, $to->date_to)->toArray();
                        foreach ($days as  $day) {
                            if ($day != $to->date_to) {
                                $per_diem = $to->philippine_region->dte->amount;
                            } else {
                                $per_diem = $to->philippine_region->dte->amount / 2;
                            }
                           
                            $entries[Str::uuid()->toString()] = [
                                'type' => 'new_entry',
                                'data' => [
                                    'date' => $day->toDateString(),
                                    'per_diem' => $per_diem,
                                    'original_per_diem' => $per_diem,
                                    'total_expenses' => 0,
                                    'breakfast' => false,
                                    'lunch' => false,
                                    'dinner' => false,
                                    'lodging' => false,
                                    'itenerary_entries' => [],
                                ],
                            ];
                        }
                    }
                    
                    $this->itenerary_entries = $entries;
                })
                ->reactive(),
            Placeholder::make('travel_order_details')
                ->visible(fn ($get) => $get('travel_order_id'))
                ->view('components.travel_orders.travel-order-details'),

            Builder::make('itenerary_entries')->blocks([
                Block::make('new_entry')->schema([
                    Flatpickr::make('date')
                        ->disableTime()
                        ->required(),
                    TextInput::make('per_diem')->disabled(),
                    Fieldset::make('Coverage')->schema([
                        Toggle::make('breakfast')->inline(false)->reactive(),
                        Toggle::make('lunch')->inline(false)->reactive(),
                        Toggle::make('dinner')->inline(false)->reactive(),
                        Toggle::make('lodging')->inline(false)->reactive(),
                    ])->columns(4),
                    Repeater::make('itenerary_entries')->schema([
                        Select::make('mot_id')
                            ->options(Mot::pluck('name', 'id'))
                            ->label('Mode of Transportation')
                            ->required(),
                        TextInput::make('place')->required(),
                        Grid::make(2)->schema([
                            Flatpickr::make('departure_time')
                                ->disableDate()
                                ->required(),
                            Flatpickr::make('arrival_time')
                                ->disableDate()
                                ->afterOrEqual('departure_time')
                                ->required(),

                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('transportation_expenses')->required()->numeric()->reactive(),
                            TextInput::make('other_expenses')->default(0)->numeric()->reactive(),
                        ]),
                    ]),
                    TextInput::make('total_expenses')->disabled()->default(0)->lte('per_diem'),
                ]),
            ])->disableItemCreation()->disableItemDeletion()->visible(fn ($get) => $get('travel_order_id')),
        ];
    }

    public function save()
    {
        $this->form->validate();
        DB::beginTransaction();
        $coverage = [];
        foreach ($this->itenerary_entries as $entry) {
            $coverage[] = [
                'date' => $entry['data']['date'],
                'per_diem' => $entry['data']['per_diem'],
                'total_expenses' => $entry['data']['total_expenses'],
                'breakfast' => $entry['data']['breakfast'],
                'lunch' => $entry['data']['lunch'],
                'dinner' => $entry['data']['dinner'],
                'lodging' => $entry['data']['lodging'],
            ];
        }

        $itenerary = Itenerary::create([
            'user_id' => auth()->id(),
            'travel_order_id' => $this->travel_order_id,
            'coverage' => $coverage,
        ]);

        foreach ($this->itenerary_entries as $itenerary_entry) {
            foreach ($itenerary_entry['data']['itenerary_entries'] as $entry) {
                $itenerary->itenerary_entries()->create([
                    'date' => $itenerary_entry['data']['date'],
                    'mot_id' => $entry['mot_id'],
                    'place' => $entry['place'],
                    'departure_time' => $entry['departure_time'],
                    'arrival_time' => $entry['arrival_time'],
                    'transportation_expenses' => $entry['transportation_expenses'],
                    'other_expenses' => $entry['other_expenses'],
                ]);
            }
        }
        DB::commit();
        Notification::make()->title('Operation Success')->body('Itenerary has been created.')->success()->send();

        return redirect()->route('requisitioner.itenerary.show', ['itenerary' => $itenerary]);
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        foreach ($this->itenerary_entries as  $key => $entry) {
            $original_per_diem = $entry['data']['original_per_diem'];
            $per_diem = $original_per_diem;
            if ($entry['data']['breakfast']) {
                $per_diem -= $original_per_diem * 0.1;
            }
            if ($entry['data']['lunch']) {
                $per_diem -= $original_per_diem * 0.1;
            }
            if ($entry['data']['dinner']) {
                $per_diem -= $original_per_diem * 0.1;
            }
            if ($entry['data']['lodging']) {
                $per_diem -= $original_per_diem * 0.5;
            }
            $this->itenerary_entries[$key]['data']['per_diem'] = $per_diem;
            $this->itenerary_entries[$key]['data']['total_expenses'] = collect($entry['data']['itenerary_entries'])->sum('transportation_expenses') + collect($entry['data']['itenerary_entries'])->sum('other_expenses');
        }

        return view('livewire.requisitioner.itenerary.itenerary-create');
    }
}
