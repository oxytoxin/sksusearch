<?php

namespace App\Http\Livewire\Archiver;

use App\Models\DisbursementVoucher;
use App\Models\LegacyDocument;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Livewire\Component;

class ViewLegacyDocuments extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return LegacyDocument::query();
    }

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('document_category')
            ->options([
                '1' => 'Disbursement Voucher',
                '2' => 'Liquidation Report',
            ]),
            MultiSelectFilter::make('fund_cluster.name')
            ->label('Fund Cluster')
            ->relationship('fund_cluster', 'name'),
            MultiSelectFilter::make('cheque_state')
            ->options([
                '1' => 'Encashed',
                '2' => 'Cancelled',
                '3' => 'Stale',
            ]),
        ];
    }
    
    protected function getTableColumns()
    {
        return [
            TextColumn::make('document_code')
                ->label('Document Code')
                ->searchable()
                ->sortable(),

            TextColumn::make('dv_number')
                ->label('DV Number')
                ->searchable()
                ->sortable(),

            TextColumn::make('payee_name')
                ->label('Payee')
                ->searchable()
                ->sortable(),

            ViewColumn::make('particulars')
                ->view('components.archiver.tables.columns.particulars-viewer')
                ->label('Particular(s)')
                ->searchable()
                ->sortable(),

            TextColumn::make('journal_date')
                ->label('Journal Date')
                ->date()
                ->searchable()
                ->sortable(),

            TextColumn::make('cheque_number')
                ->label('Cheque Number')
                ->searchable(),

            TextColumn::make('cheque_amount')
                ->label('Cheque Amount')
                ->sortable()
                ->searchable(),

            TextColumn::make('cheque_date')
                ->label('Cheque Date')
                ->date()
                ->sortable()
                ->searchable(),
           
            TextColumn::make('cheque_state')
                ->label('Cheque State')
                ->enum([
                    '1' => 'Encashed',
                    '2' => 'Cancelled',
                    '3' => 'Stale',
                ]),
            TextColumn::make('fund_cluster.name')->label('Fund Cluster')
                ->searchable()->sortable(),

            TextColumn::make('document_category')
                ->label('Document Category')
                ->enum([
                    '1' => 'Disbursement Voucher',
                    '2' => 'Liquidation Report',
                ])
                ->searchable()->sortable(),

        ];
    }

    private function getTableActions()
    {
        return [
            ActionGroup::make([
                ViewAction::make('legacy_document_details')
                    ->label('View Details')
                    ->icon('ri-list-check-2'),
                ViewAction::make('legacy_document_preview')
                    ->label('View Scanned Documents')
                    ->url(fn (LegacyDocument $record): string => route('archiver.view-scanned-docs-lgc', [$record]))
                    ->icon('ri-file-copy-2-line'),
                ViewAction::make('legacy_document_generate_qr')
                    ->label('Generate QR')
                    ->modalHeading('QR CODE')
                    ->modalContent(fn ($record) => view('components.archiver.tables.columns.legacy-document-qr', [
                        'legacy_document' => $record,
                    ]))
                    ->modalWidth('xs')
                    ->icon('ri-qr-code-line'),
                ViewAction::make('legacy_document_edit')
                    ->label('Edit')
                    ->url(fn (LegacyDocument $record): string => route('archiver.archive-leg-doc.update', [$record]))
                    ->icon('ri-edit-2-line'),
            ])->icon('ri-flashlight-fill'),
            
        ];
    }
    public function render()
    {
        return view('livewire.archiver.view-legacy-documents');
    }
}
