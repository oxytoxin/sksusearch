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

class ViewReportedSupply extends Component implements HasTable
{
    use InteractsWithTable;
    use Actions;
    public $record;

    protected function getTableQuery()
    {
        return ReportedSupply::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('user.employee_information.full_name')->html()->wrap()->label('Reported By')->searchable(),
            Tables\Columns\TextColumn::make('supply.particulars')->html()->wrap()->label('Particulars')->searchable(),
            Tables\Columns\TextColumn::make('supply.specifications')->searchable(),
            Tables\Columns\TextColumn::make('errorQuery.description')->label('Error Query')->searchable(),
            Tables\Columns\TextColumn::make('note')->html()->wrap(),
            Tables\Columns\TextColumn::make('status'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
                Action::make('view')
                ->url(fn (ReportedSupply $record): string => route('wfp.report-supply-view-details', [$record]))
                ->label('View Details')
                ->button()
                ->icon('heroicon-o-eye')
                ->color('primary')
            ];
    }

    public function render()
    {
        return view('livewire.w-f-p.view-reported-supply');
    }
}
