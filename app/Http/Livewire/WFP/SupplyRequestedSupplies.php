<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Carbon\Carbon;
use App\Models\WfpRequestedSupply;
use WireUi\Traits\Actions;
use Filament\Tables\Actions\Action;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;



class SupplyRequestedSupplies extends Component implements HasTable
{
    use InteractsWithTable;
    use Actions;

    public $record;

    protected function getTableQuery()
    {
        return WfpRequestedSupply::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('user.name')->label('Requested By')
            ->searchable(query: function (Builder $query, string $search): Builder {
                return $query->whereHas('user.employee_information', function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%");
                });}),
            Tables\Columns\TextColumn::make('particulars')->html()->wrap()->label('Particular')->searchable(),
            Tables\Columns\TextColumn::make('specification')->wrap()->searchable(),
            Tables\Columns\TextColumn::make('unit_cost')
            ->formatStateUsing(fn ($record) => '₱ '.number_format($record->unit_cost, 2))
            ->label('Unit Cost')->searchable(),
            BadgeColumn::make('is_ppmp')
            ->label('PPMP')
            ->enum([
                1 => 'Yes',
                0 => 'No',
            ])
            ->colors([
                'success' => 1,
                'danger' => 0,
            ]),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Date Requested')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->format('F d, Y h:i A'))
            ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('status')->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
                Action::make('view')
                ->url(fn (WfpRequestedSupply $record): string => route('wfp.request-supply-view', [$record]))
                ->label('View Details')
                ->button()
                ->icon('heroicon-o-eye')
                ->color('primary')
            ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
            ->options([
                'Pending' => 'Pending',
                'Forwarded to Supply' => 'Forwarded to Supply',
                'Request Modification' => 'Request Modification',
                'Supply Code Assigned' => 'Supply Code Assigned',
                'Request Rejected by Supply' => 'Request Rejected by Supply',
                'Forwarded to Accounting' => 'Forwarded to Accounting',
                'Accounting Assigned Data' => 'Accounting Assigned Data',
                'Request Rejected by Accounting' => 'Request Rejected by Accounting',
                'Accounting Assigned Data' => 'Accounting Assigned Data',
            ])
        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.supply-requested-supplies');
    }
}
