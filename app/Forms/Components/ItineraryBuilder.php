<?php

    namespace App\Forms\Components;

    use App\Models\Mot;
    use Filament\Forms\Components\Builder;
    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\Fieldset;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Toggle;

    class ItineraryBuilder
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
                        Repeater::make('itinerary_entries')->schema([
                            Grid::make([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 6,
                            ])
                                ->schema([
                                    Select::make('mot_id')
                                        ->options(Mot::pluck('name', 'id'))
                                        ->label('Mode of Transport')
                                        ->required(),
                                    TextInput::make('place')->required(),
                                    Flatpickr::make('departure_time')
                                        ->disableDate()
                                        ->required(),
                                    Flatpickr::make('arrival_time')
                                        ->disableDate()
                                        ->afterOrEqual('departure_time')
                                        ->required(),
                                    TextInput::make('transportation_expenses')
                                        ->label('Transportation')->default(0)->required()->numeric()->reactive(),
                                    TextInput::make('other_expenses')
                                        ->label('Others')->default(0)->numeric()->reactive(),
                                ])
                        ]),
                    ]),
                ]);
        }
    }
