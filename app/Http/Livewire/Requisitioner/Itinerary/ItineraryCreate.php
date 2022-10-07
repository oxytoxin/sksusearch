<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Forms\Components\Flatpickr;
use App\Models\Itinerary;
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

class ItineraryCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $travel_order_id;

    public $itinerary_entries = [];

    public function getFormSchema()
    {
        return [
            Select::make('travel_order_id')
                ->label('Travel Order')
                ->searchable()
                ->preload()
                ->options(TravelOrder::approved()
                    ->whereDoesntHave('itineraries', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->whereHas('applicants', function ($query) {
                        $query->whereUserId(auth()->id());
                    })
                    ->whereIn('travel_order_type_id', [TravelOrderType::OFFICIAL_BUSINESS, TravelOrderType::OFFICIAL_TIME])
                    ->pluck('tracking_code', 'id'))
                ->afterStateUpdated(function () {
                    $to = TravelOrder::with('philippine_region.dte')->find($this->travel_order_id);
                    $entries = [];
                    if (isset($to)) {
                        $days = CarbonPeriod::between($to->date_from, $to->date_to)->toArray();
                        foreach ($days as  $day) {
                            if ($to->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS) {
                                if ($day != $to->date_to) {
                                    $per_diem = $to->philippine_region->dte->amount;
                                } else {
                                    $per_diem = $to->philippine_region->dte->amount / 2;
                                }
                            } else {
                                $per_diem = 0;
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
                                    'itinerary_entries' => [],
                                ],
                            ];
                        }
                    }

                    $this->itinerary_entries = $entries;
                })
                ->reactive(),
            Placeholder::make('travel_order_details')
                ->visible(fn ($get) => $get('travel_order_id'))
                ->view('components.travel_orders.travel-order-details'),

            Builder::make('itinerary_entries')->blocks([
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
                    Repeater::make('itinerary_entries')->schema([
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
                            TextInput::make('transportation_expenses')->default(0)->required()->numeric()->reactive(),
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
        foreach ($this->itinerary_entries as $entry) {
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

        $itinerary = Itinerary::create([
            'user_id' => auth()->id(),
            'travel_order_id' => $this->travel_order_id,
            'coverage' => $coverage,
        ]);

        foreach ($this->itinerary_entries as $itinerary_entry) {
            foreach ($itinerary_entry['data']['itinerary_entries'] as $entry) {
                $itinerary->itinerary_entries()->create([
                    'date' => $itinerary_entry['data']['date'],
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
        Notification::make()->title('Operation Success')->body('Itinerary has been created.')->success()->send();

        return redirect()->route('requisitioner.itinerary.show', ['itinerary' => $itinerary]);
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        foreach ($this->itinerary_entries as  $key => $entry) {
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
            $transportation_expenses = 0;
            $other_expenses = 0;
            foreach ($entry['data']['itinerary_entries'] as $expense) {
                $transportation_expenses += $expense['transportation_expenses'] == '' ? 0 : $expense['transportation_expenses'];
                $other_expenses += $expense['other_expenses'] == '' ? 0 : $expense['other_expenses'];
            }
            $this->itinerary_entries[$key]['data']['per_diem'] = $per_diem;
            $this->itinerary_entries[$key]['data']['total_expenses'] = $transportation_expenses + $other_expenses;
        }

        return view('livewire.requisitioner.itinerary.itinerary-create');
    }
}
