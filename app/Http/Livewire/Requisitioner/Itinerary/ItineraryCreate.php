<?php

    namespace App\Http\Livewire\Requisitioner\Itinerary;

    use App\Forms\Components\Flatpickr;
    use App\Forms\Components\ItineraryCreateEntriesBuilder;
    use App\Models\Itinerary;
    use App\Models\Mot;
    use App\Models\TravelOrder;
    use App\Models\TravelOrderType;
    use Awcodes\FilamentTableRepeater\Components\TableRepeater;
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
    use Carbon\CarbonTimeZone;
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

        public $itinerary;

        public bool $is_editing = false;

        public $itinerary_entries = [];

        public function getFormSchema(): array
        {
            return [
                Select::make('travel_order_id')
                    ->label('Travel Order')
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        if ($this->is_editing && $this->travel_order) {
                            return [
                                $this->travel_order->id => "{$this->travel_order->purpose} ( {$this->travel_order->tracking_code} )",
                            ];
                        }

                        return TravelOrder::whereDoesntHave('itineraries', function ($query) {
                            $query->where('user_id', auth()->id());
                        })
                            ->whereHas('applicants', function ($query) {
                                $query->whereUserId(auth()->id());
                            })
                            ->whereIn('travel_order_type_id',
                                [TravelOrderType::OFFICIAL_BUSINESS, TravelOrderType::OFFICIAL_TIME])
                            ->select(
                                DB::raw("CONCAT(purpose,' ( ',tracking_code,' )') AS tcAndP"),
                                'id'
                            )
                            ->pluck('tcAndP', 'id');
                    })
                    ->disabled(fn() => $this->is_editing)
                    ->afterStateUpdated(function ($state) {
                        $this->travel_order = TravelOrder::find($state);
                        $this->generateItineraryEntries();
                    })
                    ->reactive(),
                Placeholder::make('itinerary_template')
                    ->label('Itinerary Template Selector')
                    ->content(fn() => view('components.travel_orders.itinerary-template-selector',
                        ['itineraries' => $this->travel_order->itineraries]))
                    ->visible(fn() => filled($this->travel_order)),
                Card::make([
                    Placeholder::make('travel_order_details')
                        ->content(fn() => view('components.travel_orders.travel-order-details', [
                            'travel_order' => $this->travel_order,
                            'itinerary_entries' => $this->itinerary_entries,
                        ])),
                ])->visible(fn($get) => $get('travel_order_id')),
                ItineraryCreateEntriesBuilder::make(),
            ];
        }

        public function save()
        {
            $entries = $this->form->validate();
            DB::beginTransaction();
            $coverage = [];
            foreach ($this->itinerary_entries as $entry) {
                $data = $entry['data'];
                $coverage[] = [
                    'date' => $data['date'],
                    'per_diem' => $data['per_diem'] ?? 0,
                    'original_per_diem' => $data['original_per_diem'] ?? 0,
                    'total_expenses' => $data['total_expenses'] ?? 0,
                    'breakfast' => $data['breakfast'] ?? false,
                    'lunch' => $data['lunch'] ?? false,
                    'dinner' => $data['dinner'] ?? false,
                    'lodging' => $data['lodging'] ?? false,
                ];
            }

            if ($this->is_editing) {
                $itinerary = $this->itinerary;
                $itinerary->update([
                    'coverage' => $coverage,
                    'approved_at' => null,
                    'submitted_at' => now(),
                ]);
                $itinerary->itinerary_entries()->delete();
            } else {
                $itinerary = Itinerary::create([
                    'user_id' => auth()->id(),
                    'travel_order_id' => $this->travel_order_id,
                    'coverage' => $coverage,
                    'submitted_at' => now(),
                ]);
            }

            foreach ($this->itinerary_entries as $itinerary_entry) {
                foreach ($itinerary_entry['data']['itinerary_entries'] ?? [] as $entry) {
                    $itinerary->itinerary_entries()->create([
                        'date' => $itinerary_entry['data']['date'],
                        'mot_id' => $entry['mot_id'],
                        'place' => $entry['place'],
                        'departure_time' => Carbon::make($entry['departure_time'])->setTimezone('Asia/Manila'),
                        'arrival_time' => Carbon::make($entry['arrival_time'])->setTimezone('Asia/Manila'),
                        'transportation_expenses' => $entry['transportation_expenses'],
                        'other_expenses' => $entry['other_expenses'],
                    ]);
                }
            }
            DB::commit();
            Notification::make()
                ->title('Operation Success')
                ->body($this->is_editing ? 'Itinerary has been resubmitted.' : 'Itinerary has been created.')
                ->success()
                ->send();

            $travel_order = TravelOrder::find($this->travel_order_id);
            if (!$this->is_editing && $travel_order && $travel_order->needs_vehicle) {
                return redirect()->route('requisitioner.motorpool.create', ['travel_order' => $travel_order]);
            }
            return redirect()->route('requisitioner.itinerary.show', ['itinerary' => $itinerary]);
        }

        public function mount()
        {
            $this->form->fill();
            $itinerary = Itinerary::find(request()->route('itinerary'));
            if ($itinerary?->exists) {
                $itinerary->load(['travel_order.itineraries', 'itinerary_entries']);

                if ($itinerary->user_id !== auth()->id() || filled($itinerary->submitted_at)) {
                    abort(403);
                }

                $this->itinerary = $itinerary;
                $this->travel_order = $itinerary->travel_order;
                $this->travel_order_id = $itinerary->travel_order_id;
                $this->is_editing = true;
                $this->form->fill(['travel_order_id' => $this->travel_order_id]);
                $this->generateItineraryEntries($itinerary);

                return;
            }

            if (request('travel_order')) {
                $to = TravelOrder::find(request('travel_order'));
                if (!$to->applicants()->where('users.id',
                        auth()->id())->exists() || $to->itineraries()->where('user_id', auth()->id())->exists()) {
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
            foreach ($this->itinerary_entries as $key => $entry) {
                $data = $entry['data'] ?? [];
                $original_per_diem = $data['original_per_diem'] ?? $this->getPerDiemForDate($data['date'] ?? null);
                $per_diem = $original_per_diem;
                if (!($data['has_per_diem'] ?? true)) {
                    $per_diem = 0;
                } else {
                    if ($data['breakfast'] ?? false) {
                        $per_diem -= $original_per_diem * 0.1;
                    }
                    if ($data['lunch'] ?? false) {
                        $per_diem -= $original_per_diem * 0.1;
                    }
                    if ($data['dinner'] ?? false) {
                        $per_diem -= $original_per_diem * 0.1;
                    }
                    if ($data['lodging'] ?? false) {
                        $per_diem -= $original_per_diem * 0.5;
                    }
                }

                $transportation_expenses = 0;
                $other_expenses = 0;
                foreach ($data['itinerary_entries'] ?? [] as $expense) {
                    $transportation_expenses += ($expense['transportation_expenses'] ?? '') == '' ? 0 : $expense['transportation_expenses'];
                    $other_expenses += ($expense['other_expenses'] ?? '') == '' ? 0 : $expense['other_expenses'];
                }
                $this->itinerary_entries[$key]['data']['original_per_diem'] = $original_per_diem;
                $this->itinerary_entries[$key]['data']['per_diem'] = $per_diem;
                $this->itinerary_entries[$key]['data']['total_expenses'] = $transportation_expenses + $other_expenses + $per_diem;
            }

            return view('livewire.requisitioner.itinerary.itinerary-create');
        }

        public function getTravelOrderCoverageDaysCount(): int
        {
            return $this->travelOrderCoverageDates()->count();
        }

        public function getTravelOrderDateFrom(): ?string
        {
            return $this->travel_order?->date_from?->toDateString();
        }

        public function getTravelOrderDateTo(): ?string
        {
            return $this->travel_order?->date_to?->toDateString();
        }

        public function getPerDiemForDate($date)
        {
            if (!$this->travel_order || !$date || $this->travel_order->travel_order_type_id != TravelOrderType::OFFICIAL_BUSINESS) {
                return 0;
            }

            try {
                $date = Carbon::make($date)?->toDateString();
            } catch (\Throwable) {
                return 0;
            }

            if (!$date) {
                return 0;
            }

            $perDiem = data_get($this->travel_order, 'philippine_region.dte.amount', 0);

            return $date === $this->travel_order->date_to->toDateString()
                ? $perDiem / 2
                : $perDiem;
        }

        public function missingTravelOrderCoverageDates($entries)
        {
            $entryDates = collect($entries)
                ->filter(fn($entry) => filled(data_get($entry, 'data.itinerary_entries')))
                ->map(function ($entry) {
                    $date = data_get($entry, 'data.date');

                    if (blank($date)) {
                        return null;
                    }

                    try {
                        return Carbon::make($date)?->toDateString();
                    } catch (\Throwable) {
                        return null;
                    }
                })
                ->filter()
                ->unique()
                ->values();

            return $this->travelOrderCoverageDates()
                ->diff($entryDates)
                ->map(fn(string $date) => Carbon::make($date)->format('M d, Y'))
                ->values();
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
                            'original_per_diem' => $coverage['original_per_diem'] ?? $coverage['per_diem'],
                            'total_expenses' => $coverage['total_expenses'],
                            'breakfast' => $coverage['breakfast'],
                            'lunch' => $coverage['lunch'],
                            'dinner' => $coverage['dinner'],
                            'lodging' => $coverage['lodging'],
                            'itinerary_entries' => $itinerary->itinerary_entries()->whereDate('date',
                                $coverage['date'])->get()->map(function ($entry) {
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
                foreach ($days as $day) {
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

        private function travelOrderCoverageDates()
        {
            if (!$this->travel_order?->date_from || !$this->travel_order?->date_to) {
                return collect();
            }

            return collect(CarbonPeriod::between($this->travel_order->date_from, $this->travel_order->date_to))
                ->map(fn($date) => $date->toDateString())
                ->values();
        }
    }
