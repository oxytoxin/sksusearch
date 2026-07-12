<?php

    namespace App\Forms\Components;

    use App\Models\Mot;
    use Awcodes\FilamentTableRepeater\Components\TableRepeater;
    use Filament\Forms\Components\Builder;
    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\Fieldset;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Hidden;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Toggle;

    class ItineraryCreateEntriesBuilder
    {

        public static function make()
        {
            return Builder::make('itinerary_entries')
                ->blocks([
                    Block::make('new_entry')->schema([
                        Grid::make(2)->schema([
                            Fieldset::make('Coverage')->schema([
                                Flatpickr::make('date')
                                    ->disableTime()
                                    ->required()
                                    ->reactive()
                                    ->afterOrEqual(fn($livewire) => $livewire->getTravelOrderDateFrom())
                                    ->beforeOrEqual(fn($livewire) => $livewire->getTravelOrderDateTo())
                                    ->afterStateUpdated(function ($state, $set, $livewire) {
                                        $perDiem = $livewire->getPerDiemForDate($state);

                                        $set('original_per_diem', $perDiem);
                                        $set('per_diem', $perDiem);
                                    })
                                    ->columnSpan(1),
                                Grid::make([
                                    'sm' => 4,
                                    'md' => 4,
                                ])
                                    ->schema([
                                        Toggle::make('breakfast')->inline(false)->reactive()->columnSpan(1)
                                            ->offColor('primary')->onColor('danger')
                                            ->offIcon('heroicon-o-check-circle')->onIcon('heroicon-o-x-circle'),
                                        Toggle::make('lunch')->inline(false)->reactive()->columnSpan(1)
                                            ->offColor('primary')->onColor('danger')
                                            ->offIcon('heroicon-o-check-circle')->onIcon('heroicon-o-x-circle'),
                                        Toggle::make('dinner')->inline(false)->reactive()->columnSpan(1)
                                            ->offColor('primary')->onColor('danger')
                                            ->offIcon('heroicon-o-check-circle')->onIcon('heroicon-o-x-circle'),
                                        Toggle::make('lodging')->inline(false)->reactive()->columnSpan(1)
                                            ->offColor('primary')->onColor('danger')
                                            ->offIcon('heroicon-o-check-circle')->onIcon('heroicon-o-x-circle'),
                                    ])->columnSpan(1),
                            ])->columnSpan(1),
                            Fieldset::make('Total Amount')->schema([
                                Hidden::make('original_per_diem')->default(0),
                                Toggle::make('has_per_diem')
                                    ->label('Has Per Diem')
                                    ->default(true)
                                    ->reactive(),
                                TextInput::make('per_diem')->disabled(),
                                TextInput::make('total_expenses')->disabled()->default(0),
                            ])->columns(1)->columnSpan(1),
                        ]),
                        TableRepeater::make('itinerary_entries')
                            ->hideLabels()
                            ->defaultItems(1)
                            ->minItems(1)
                            ->createItemButtonLabel('Add itinerary row')
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
                                    ->disabled(fn($get) => $get('mot_id') == 13)
                                    ->reactive(),
                                TextInput::make('other_expenses')->label('Others')->default(0)->numeric()->reactive(),
                            ])
                    ]),
                ])
                ->createItemButtonLabel('Add itinerary entry')
                ->createItemBetweenButtonLabel('Add itinerary entry')
                ->minItems(fn($livewire) => $livewire->getTravelOrderCoverageDaysCount())
                ->rule(fn($livewire) => function (string $attribute, $value, $fail) use ($livewire) {
                    $missingDates = $livewire->missingTravelOrderCoverageDates($value ?? []);

                    if ($missingDates->isNotEmpty()) {
                        $fail('Please add itinerary entries for every travel order coverage date. Missing: '.$missingDates->join(', '));
                    }
                })
                ->visible(fn($get) => $get('travel_order_id'));

        }
    }
