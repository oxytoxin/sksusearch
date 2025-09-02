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

class WfpSubmissionsQ1 extends Component implements HasTable
{
     use InteractsWithTable;

    public $wfp_type;
    public $fund_cluster;
    public $isPresident;

    public $supplementalQuarterId = null;

     protected $queryString = ['supplementalQuarterId'];

     public $data = [
        'wfp_type_id' => 1,
     ];

     public function mount()
     {
        $this->isPresident = auth()->user()->employee_information->office_id == 51 && auth()->user()->employee_information->position_id == 34;
        if(session()->has('fund_cluster2'))
        {
            $this->fund_cluster = session('fund_cluster2');
        }else{
            $this->fund_cluster = 1;
        }
        // if($filter)
        // {
        //     $this->filter($filter);
        // }else{

        //     $this->fund_cluster = 1;
        // }
        $this->wfp_type = WpfType::all()->count();
     }

    protected function getTableQuery()
    {
        return Wfp::query()
            ->when(!is_null($this->supplementalQuarterId), function (Builder $query) {
                return $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
            })
            ->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
    }

     protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('id')->label('ID')->toggleable(isToggledHiddenByDefault: true)->searchable(),
            Tables\Columns\TextColumn::make('wfpType.description')->label('WFP Period')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.name')->label('Name')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.office.name')->label('Office')->searchable(),
            Tables\Columns\TextColumn::make('fundClusterWfp.name')->label('Fund Cluster')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.mfo.name')->label('MFO')->searchable(),
            Tables\Columns\TextColumn::make('fund_description')->searchable(),
            Tables\Columns\TextColumn::make('updated_at')
            ->label('Date Created')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->updated_at)->format('F d, Y h:i A'))
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
                ->url(fn ($record): string => route('wfp.print-wfp', ['record' => $record, 'isSupplemental' => 1,'supplementalQuarterId'=>$this->supplementalQuarterId,'wfpType' => $this->data['wfp_type_id'],'costCenter'=> $record->cost_center_id])),
                Action::make('view ppmp')
                ->label('View PPMP')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-ppmp', ['record' => $record, 'isSupplemental' => 1,'wfpType' => $this->data['wfp_type_id'],'costCenterId' => $record->cost_center_id,'supplementalQuarterId'=> $this->supplementalQuarterId])),
                Action::make('view pre')
                ->label('View PRE')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-pre', ['record'=> $record, 'isSupplemental' => 1]))
            ]),
            Action::make('approve')
            ->label('Approve WFP')
            ->color('warning')
            ->button()
            ->icon('heroicon-o-check-circle')
            ->action(fn ($record) => $record->update(['is_approved' => 1]))
            ->requiresConfirmation()
            ->visible(fn ($record) => $record->is_approved === 0 && !$this->isPresident),
            Action::make('modify')
            ->label('Request Modification')
            ->color('danger')
            ->button()
            ->icon('heroicon-o-pencil-alt')
            ->form([
                Forms\Components\Textarea::make('reason')
                ->label('Reason for Modification')
                ->required()

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

                //delete drafts (FIX)
                // $record->fundAllocation->fundDraft->draft_amounts()->delete();
                // $record->fundAllocation->fundDraft->draft_items()->delete();
                // $record->fundAllocation->fundDraft->delete();
            })->requiresConfirmation()
            ->visible(fn ($record) => $record->is_approved === 0 && !$this->isPresident),
        ];
    }

     protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('wfp_type')
            ->form([
                Forms\Components\Select::make('wfp_type')
                ->label('WFP Period')
                ->options(WpfType::all()->pluck('description', 'id')->prepend('All', ''))
            ])
            ->query(function (Builder $query, array $data): Builder {
                if (!empty($data['wfp_type'])) {
                    $this->data['wfp_type_id'] = $data['wfp_type'];
                    return $query->where('wpf_type_id', $data['wfp_type']);
                }
                return $query; // Return the original query if "All" is selected
            }),
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
            Filter::make('is_approved')
            ->form([
                Forms\Components\Select::make('is_approved')
                ->label('Status')
                ->options([
                    '' => 'All',
                    0 => 'Pending',
                    1 => 'Approved',
                    500 => 'For Modification'
                ])
            ])
            ->query(function (Builder $query, array $data): Builder {
                if (!empty($data['is_approved'])) {
                    return $query->where('is_approved', $data['is_approved']);
                }
                return $query; // Return the original query if "All" is selected
            }),
        ];
    }

    public function filter($id)
    {
        $this->fund_cluster = $id;
        session(['fund_cluster2' => $id]);
    }

    public function render()
    {
        return view('livewire.w-f-p.wfp-submissions-q1');
    }
}
