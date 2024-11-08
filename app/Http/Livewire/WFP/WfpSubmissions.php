<?php

namespace App\Http\Livewire\WFP;

use App\Models\MFO;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\WpfType;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use App\Models\Wfp;
use App\Models\WfpApprovalRemark;
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
            Tables\Columns\TextColumn::make('is_approved')
            ->label('Status')
            ->formatStateUsing(function ($record) {
                if($record->is_approved === 0)
                {
                    return 'Pending';
                }elseif($record->is_approved === 1)
                {
                    return 'Approved';
                }elseif($record->is_approved === 500){
                    return 'For Modification';
                }
            })
        ];
    }

    public function getTableActions()
    {
        return [
            Tables\Actions\ActionGroup::make([
                Action::make('view wfp')
                ->label('View WFP')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-wfp', $record)),
                Action::make('view ppmp')
                ->label('View PPMP')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-ppmp', $record)),
                Action::make('view pre')
                ->label('View PRE')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-pre', $record))
            ]),
            Action::make('approve')
            ->label('Approve WFP')
            ->color('warning')
            ->button()
            ->icon('heroicon-o-check-circle')
            ->action(fn ($record) => $record->update(['is_approved' => 1]))
            ->requiresConfirmation()
            ->visible(fn ($record) => $record->is_approved === 0),
            Action::make('modify')
            ->label('Request Modification')
            ->color('danger')
            ->button()
            ->icon('heroicon-o-pencil-alt')
            ->form([
                Forms\Components\RichEditor::make('reason')
                ->label('Reason for Modification')
                ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'edit',
                        'italic',
                        'orderedList',
                        'preview',
                    ])
            ])
            ->action(function ($record, $data) {
                WfpApprovalRemark::create([
                    'wfps_id' => $record->id,
                    'user_id' => auth()->user()->id,
                    'remarks' => $data['reason']
                ]);
                $record->update([
                    'is_approved' => 500
                ]);
            })->requiresConfirmation()
            ->visible(fn ($record) => $record->is_approved === 0),
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
