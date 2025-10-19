<?php

    namespace App\Http\Livewire\Archiver;

    use App\Models\DisbursementVoucher;
    use App\Models\FundCluster;
    use Filament\Tables\Actions\Action;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Columns\ViewColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Filters\MultiSelectFilter;
    use Filament\Tables\Table;
    use Illuminate\Database\Eloquent\Builder;
    use Livewire\Component;
    use Filament\Forms;
    use Filament\Tables\Filters\Filter;

    class ViewArchives extends Component implements HasTable
    {
        use InteractsWithTable;

        protected function getTableFilters(): array
        {
            return [
                MultiSelectFilter::make('fund_cluster_name')
                    ->label('Fund Cluster')
                    ->options(fn() => FundCluster::whereIn('id', [1, 2, 3, 8])->pluck('name', 'id')->toArray())
                    ->relationship('fund_cluster', 'name'),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Uploaded From'),
                        Forms\Components\DatePicker::make('created_until')->label('Uploaded To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })

            ];
        }

        protected function getTableQuery()
        {
            return DisbursementVoucher::where('current_step_id', '>=', '23000');
        }

        protected function getTableColumns()
        {
            return [
                TextColumn::make('tracking_number')
                    ->searchable(),
                TextColumn::make('user.employee_information.full_name')
                    ->searchable()
                    ->label('Requisitioner'),
                TextColumn::make('payee')
                    ->searchable()
                    ->label('Payee'),
                TextColumn::make('fund_cluster.name')
                    ->searchable()
                    ->label('Fund Cluster'),
                TextColumn::make('cheque_number')
                    ->searchable()
                    ->label('Cheque / ADA'),
                ViewColumn::make('disbursment_voucher_particulars.purpose')
                    ->view('components.archiver.tables.columns.particulars-viewer-nlgc')
                    ->label('Particular(s)'),
                TextColumn::make('disbursement_voucher_particulars_sum_amount')
                    ->sum('disbursement_voucher_particulars', 'amount')
                    ->label('Amount')
                    ->money('php', true),
                TextColumn::make('created_at')
                    ->label('Date uploaded')
                    ->searchable()->date(),
            ];
        }

        protected function getTableActions(): array
        {
            return [
                Action::make('view_scanned_documents')
                    ->url(fn(DisbursementVoucher $record): string => route('archiver.view-scanned-docs', [$record]))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-eye')
                    ->label('')
            ];
        }

        public function render()
        {
            return view('livewire.archiver.view-archives');
        }
    }
