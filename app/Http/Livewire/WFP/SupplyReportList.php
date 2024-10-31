<?php

namespace App\Http\Livewire\WFP;

use Carbon\Carbon;
use App\Models\Wfp;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\ErrorQuery;
use WireUi\Traits\Actions;
use App\Models\ReportedSupply;
use App\Models\WfpRequestedSupply;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;

class SupplyReportList extends Component implements HasTable
{
    use InteractsWithTable;
    use Actions;

    public $record;

    protected function getTableQuery()
    {
        return ReportedSupply::query()->where('user_id', Auth::id());
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('supply.particulars')->html()->wrap()->label('Particulars')->searchable(),
            Tables\Columns\TextColumn::make('supply.specifications')->searchable(),
            Tables\Columns\TextColumn::make('errorQuery.description')->label('Error Query')->searchable(),
            Tables\Columns\TextColumn::make('note')->html()->wrap(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
                Action::make('edit')
                ->label('Edit Query')
                ->color('warning')
                ->button()
                ->icon('heroicon-s-pencil')
                ->mountUsing(fn (Forms\ComponentContainer $form, $record) => $form->fill([
                    'error_query_id' => $record->error_query_id,
                    'note' => $record->note,
                ]))
                ->form([
                    Select::make('error_query_id')
                    ->label('Error Type')
                    ->options(ErrorQuery::pluck('description', 'id')->toArray())
                    ->required(),
                    Textarea::make('note')
                    ->formatStateUsing(fn ($record) => strip_tags($record->note ?? ''))
                ])->action(function (array $data, $record): void {
                    $record->error_query_id = $data['error_query_id'];
                    $record->note = $data['note'];
                    $record->save();
                })->requiresConfirmation(),
                // ->visible(fn ($record) => $record->status === 'Pending' || $record->status === 'Request Modification'),
                Action::make('view')
                ->url(fn (ReportedSupply $record): string => route('wfp.report-supply-view-details', [$record]))
                ->label('View Details')
                ->button()
                ->icon('heroicon-o-eye')
                ->color('primary')
            ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('new_request')
            ->icon('heroicon-o-plus-circle')
            ->button()
            ->url(fn (): string => route('wfp.report-supply'))
       ];
    }

    public function render()
    {
        return view('livewire.w-f-p.supply-report-list');
    }
}
