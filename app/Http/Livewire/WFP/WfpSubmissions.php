<?php

namespace App\Http\Livewire\WFP;

use App\Models\MFO;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\WpfType;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use App\Models\Wfp;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;

class WfpSubmissions extends Component implements HasTable
{
    use InteractsWithTable;

    public $wfp_type;
    public $fund_cluster;

    public function mount()
    {
        $this->fund_cluster = 1;
        $this->wfp_type = WpfType::all()->count();
    }

    protected function getTableQuery()
    {
        return Wfp::query()->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('wfpType.description')->label('WFP Type')->searchable(),
            Tables\Columns\TextColumn::make('fundClusterWfp.name')->label('Fund Cluster')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.mfo.name')->label('MFO')->searchable(),
            Tables\Columns\TextColumn::make('fund_description')->searchable(),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Date Created')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->format('F d, Y h:i A'))
            ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('user.employee_information.full_name')
            ->label('Created By')
            ->searchable()->sortable(),
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('view wfp')
            ->button()
            ->icon('heroicon-o-eye')
            ->url(fn ($record): string => route('wfp.print-wfp', $record)),
            Action::make('view ppmp')
            ->button()
            ->icon('heroicon-o-eye')
            ->url(fn ($record): string => route('wfp.print-ppmp', $record))
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('mfo')
            ->form([
                Forms\Components\Select::make('mfo')
                ->options(MFO::all()->pluck('name', 'id')->prepend('All', ''))
            ])
            ->query(function (Builder $query, array $data): Builder {
                if (!empty($data['mfo'])) {
                    return $query->whereHas('costCenter', function($query) use ($data) {
                        $query->where('m_f_o_s_id', $data['mfo']);
                    });
                }
                return $query; // Return the original query if "All" is selected
            }),
        ];
    }

    public function filter($id)
    {
        $this->fund_cluster = $id;
    }

    public function render()
    {
        return view('livewire.w-f-p.wfp-submissions');
    }
}
