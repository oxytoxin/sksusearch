<?php

namespace App\Http\Livewire\Archiver;

use App\Models\ArchivedCheque;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Position;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\MultiSelectFilter;

class ViewStaleCheques extends Component implements HasTable
{
    use InteractsWithTable;

    public function mount()
    {
        $this->form->fill();
    //  if($document_code !=""){
    //     $this->form->fill(["tableSearchQuery"=>$document_code]);
    //  }
    }

    protected function getTableQuery()
    {
        return ArchivedCheque::query();
    }

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('cheque_state')
            ->options([
                '1' => 'Cancelled',
                '2' => 'Stale',
            ]),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('payee')
                ->label('Payee Name')
                ->searchable(),

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
                    '1' => 'Cancelled',
                    '2' => 'Stale',
                ]),
        ];
    }
    private function getTableActions()
    {
        return [
            ActionGroup::make([
                // ViewAction::make('legacy_document_details')
                //     ->label('View Details')
                //     ->icon('ri-list-check-2')
                //     ->modalContent(fn ($record) => view('components.archiver.tables.columns.legacy-document-details', [
                //         'legacy_document' => $record,
                //     ])),
                ViewAction::make('legacy_document_preview')
                    ->label('View Scanned Documents')
                    ->url(fn (ArchivedCheque $record): string => route('archiver.view-scanned-docs-chq', [$record,0]))
                    ->openUrlInNewTab()
                    ->icon('ri-file-copy-2-line'),
                // ViewAction::make('legacy_document_generate_qr')
                //     ->label('Generate QR')
                //     ->modalHeading('QR CODE')
                //     ->modalContent(fn ($record) => view('components.archiver.tables.columns.legacy-document-qr', [
                //         'legacy_document' => $record,
                //     ]))
                //     ->modalWidth('xs')
                //     ->icon('ri-qr-code-line'),
                // ViewAction::make('legacy_document_edit')
                //     ->label('Edit')
                //     ->url(fn (LegacyDocument $record): string => route('archiver.archive-leg-doc.update', [$record]))
                //     ->icon('ri-edit-2-line'),
            ])->icon('ri-flashlight-fill'),
            
        ];
    }
    protected function getTableActionsPosition(): ?string
    {
        return Position::AfterCells;
    }
    public function render()
    {
        return view('livewire.archiver.view-stale-cheques');
    }
}
