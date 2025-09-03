<?php

    namespace App\Forms\Components;

    use App\Models\Mot;
    use Awcodes\FilamentTableRepeater\Components\TableRepeater;
    use Filament\Forms\Components\Builder;
    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\Fieldset;
    use Filament\Forms\Components\Grid;
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
                                    ->disabled(fn($get) => $get('mot_id') == 13)
                                    ->reactive(),
                                TextInput::make('other_expenses')->label('Others')->default(0)->numeric()->reactive(),
                            ])
                    ]),
                ])
                ->disableItemCreation()
                ->disableItemDeletion()
                ->visible(fn($get) => $get('travel_order_id'));

        }
    }
