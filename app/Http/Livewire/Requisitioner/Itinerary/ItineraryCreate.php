<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Forms\Components\Flatpickr;
use App\Models\Itinerary;
use App\Models\Mot;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Card;
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

    public $travel_order;

    public $itinerary_entries = [];

    public function getFormSchema()
    {
        return [
            Select::make('travel_order_id')
                ->label('Travel Order')
                ->searchable()
                ->preload()
                ->options(
                    TravelOrder::whereDoesntHave('itineraries', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                        ->whereHas('applicants', function ($query) {
                            $query->whereUserId(auth()->id());
                        })
                        ->whereIn('travel_order_type_id', [TravelOrderType::OFFICIAL_BUSINESS, TravelOrderType::OFFICIAL_TIME])
                        ->select(
                            DB::raw("CONCAT(purpose,' ( ',tracking_code,' )') AS tcAndP"),
                            'id'
                        )
                        ->pluck('tcAndP', 'id')
                )
                ->afterStateUpdated(function ($state) {
                    $this->travel_order = TravelOrder::find($state);
                    $this->generateItineraryEntries();
                })
                ->reactive(),
            Placeholder::make('itinerary_template')
                ->label('Itinerary Template Selector')
                ->content(fn () => view('components.travel_orders.itinerary-template-selector', ['itineraries' => $this->travel_order->itineraries]))
                ->visible(fn () => filled($this->travel_order)),
            Card::make([
                Placeholder::make('travel_order_details')
                    ->content(fn () => view('components.travel_orders.travel-order-details', [
                        'travel_order' => $this->travel_order,
                        'itinerary_entries' => $this->itinerary_entries,
                    ])),
            ])->visible(fn ($get) => $get('travel_order_id')),
            Builder::make('itinerary_entries')->blocks([
                Block::make('new_entry')->schema([
                    Grid::make(2)->schema([
                        Fieldset::make('Coverage')->schema([
                            Flatpickr::make('date')
                                ->disableTime()
                                ->required()
                                ->disabled()
                                ->columnSpan(1),
                            Grid::make([
                                'sm' => 4,
                                'md' => 4,
                            ])
                                ->schema([
                                    Toggle::make('breakfast')->inline(false)->reactive()->columnSpan(1),
                                    Toggle::make('lunch')->inline(false)->reactive()->columnSpan(1),
                                    Toggle::make('dinner')->inline(false)->reactive()->columnSpan(1),
                                    Toggle::make('lodging')->inline(false)->reactive()->columnSpan(1),
                                ])->columnSpan(1),
                        ])->columnSpan(1),
                        Fieldset::make('Total Amount')->schema([
                            Toggle::make('has_per_diem')
                                ->label('Has Per Diem')
                                ->reactive(),
                            TextInput::make('per_diem')->disabled(),
                            TextInput::make('total_expenses')->disabled()->default(0),
                        ])->columns(1)->columnSpan(1),
                    ]),
                    TableRepeater::make('itinerary_entries')
                        ->hideLabels()
                        ->schema([
                            Select::make('mot_id')
                                ->options(Mot::pluck('name', 'id'))
                                ->label('Mode of Transport')
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state == 13) {
                                        $set('transportation_expenses', 0);
                                    }
                                })
                                ->required(),
                            TextInput::make('place')->required(),
                            Flatpickr::make('departure_time')
                                ->disableDate()
                                ->required(),
                            Flatpickr::make('arrival_time')
                                ->disableDate()
                                // ->afterOrEqual('departure_time')
                                ->required(),
                            TextInput::make('transportation_expenses')->label('Transportation')
                                ->default(0)
                                ->required()
                                ->numeric()
                                ->disabled(fn ($get) => $get('mot_id') == 13)
                                ->reactive(),
                            TextInput::make('other_expenses')->label('Others')->default(0)->numeric()->reactive(),
                        ])
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
        if (request('travel_order')) {
            $to = TravelOrder::find(request('travel_order'));
            if (!$to->applicants()->where('users.id', auth()->id())->exists() || $to->itineraries()->where('user_id', auth()->id())->exists()) {
                abort(403);
            }
            if ($to) {
                $this->travel_order = $to;
                $this->travel_order_id = $to->id;
                $this->generateItineraryEntries();
            }
        }
    }

    public function copyItinerary(Itinerary $itinerary)
    {
        $this->generateItineraryEntries($itinerary);
        Notification::make()->title('Operation Success')->body('Itinerary copied.')->success()->send();
    }

    public function clearItinerary()
    {
        $this->generateItineraryEntries();
        Notification::make()->title('Operation Success')->body('Itinerary has been cleared.')->success()->send();
    }

    public function render()
    {
        foreach ($this->itinerary_entries as  $key => $entry) {
            $original_per_diem = $entry['data']['original_per_diem'];
            $per_diem = $original_per_diem;
            if (!$entry['data']['has_per_diem']) {
                $per_diem = 0;
            } else {
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
            }

            $transportation_expenses = 0;
            $other_expenses = 0;
            foreach ($entry['data']['itinerary_entries'] as $expense) {
                $transportation_expenses += $expense['transportation_expenses'] == '' ? 0 : $expense['transportation_expenses'];
                $other_expenses += $expense['other_expenses'] == '' ? 0 : $expense['other_expenses'];
            }
            $this->itinerary_entries[$key]['data']['per_diem'] = $per_diem;
            $this->itinerary_entries[$key]['data']['total_expenses'] = $transportation_expenses + $other_expenses + $per_diem;
        }

        return view('livewire.requisitioner.itinerary.itinerary-create');
    }


    private function generateItineraryEntries($itinerary = null)
    {
        $to = $this->travel_order;
        $entries = [];
        if ($itinerary) {
            foreach ($itinerary->coverage as $coverage) {
                $entries[Str::uuid()->toString()] = [
                    'type' => 'new_entry',
                    'data' => [
                        'date' => $coverage['date'],
                        'per_diem' => $coverage['per_diem'],
                        'has_per_diem' => true,
                        'original_per_diem' => $coverage['per_diem'],
                        'total_expenses' => $coverage['total_expenses'],
                        'breakfast' => $coverage['breakfast'],
                        'lunch' => $coverage['lunch'],
                        'dinner' => $coverage['dinner'],
                        'lodging' => $coverage['lodging'],
                        'itinerary_entries' => $itinerary->itinerary_entries()->whereDate('date', $coverage['date'])->get()->map(function ($entry) {
                            return [
                                'mot_id' => $entry->mot_id,
                                'place' => $entry->place,
                                'departure_time' => $entry->departure_time,
                                'arrival_time' => $entry->arrival_time,
                                'transportation_expenses' => $entry->transportation_expenses,
                                'other_expenses' => $entry->other_expenses,
                            ];
                        })->toArray(),
                    ],
                ];
            }
        } elseif (isset($to)) {
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
                        'has_per_diem' => true,
                        'original_per_diem' => $per_diem,
                        'total_expenses' => 0,
                        'breakfast' => false,
                        'lunch' => false,
                        'dinner' => false,
                        'lodging' => false,
                        'itinerary_entries' => [
                            [
                                'mot_id' => null,
                                'place' => '',
                                'departure_time' => null,
                                'arrival_time' => null,
                                'transportation_expenses' => 0,
                                'other_expenses' => 0,
                            ],
                        ],
                    ],
                ];
            }
        }
        $this->itinerary_entries = $entries;
    }
}
