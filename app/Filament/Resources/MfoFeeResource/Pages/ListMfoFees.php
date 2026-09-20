<?php

    namespace App\Filament\Resources\MfoFeeResource\Pages;

    use App\Filament\Resources\MfoFeeResource;
    use Filament\Actions;
    use Filament\Resources\Pages\ListRecords;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Table;

    class ListMfoFees extends ListRecords
    {
        protected static string $resource = MfoFeeResource::class;

        public function table(Table $table): Table
        {
            return parent::table($table)
                ->filters([
                    SelectFilter::make('m_f_o_s_id')->label('MFO')->relationship('mfo', 'name'),
                    SelectFilter::make('fund_cluster_id')->label('Fund Cluster')->relationship('fundClusterWFP', 'name'),
                ], FiltersLayout::AboveContent);
        }

        protected function getHeaderActions(): array
        {
            return [
                Actions\CreateAction::make()
                    ->label('New MFO Fee')
                    ->color('success'),
            ];
        }
    }
