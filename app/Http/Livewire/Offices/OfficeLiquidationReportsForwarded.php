<?php

namespace App\Http\Livewire\Offices;

use Livewire\Component;
use App\Models\LiquidationReport;
use App\Models\LiquidationReportStep;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class OfficeLiquidationReportsForwarded extends Component implements HasTable
{
    use InteractsWithTable;

    protected $listeners = ['refresh' => '$refresh'];

    protected function getTableQuery()
    {
        $office_final_step_id = auth()->user()->employee_information->office->office_group->liquidation_report_final_step->id;
        return LiquidationReport::whereForCancellation(false)->where('current_step_id', '>', $office_final_step_id)->latest();
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('requisitioner.employee_information.full_name')
                ->label('Requisitioner'),
            TextColumn::make('disbursement_voucher.tracking_number'),
            TextColumn::make('report_date')->date()->label('Date'),
        ];
    }

    protected function getTableActions()
    {
        return [
            ActionGroup::make([
                ViewAction::make('progress')
                    ->label('Progress')
                    ->icon('ri-loader-4-fill')
                    ->modalHeading('Liquidation Report Progress')
                    ->modalContent(fn ($record) => view('components.timeline_views.progress_logs', [
                        'record' => $record,
                        'steps' => LiquidationReportStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                    ])),
                ViewAction::make('logs')
                    ->label('Activity Timeline')
                    ->icon('ri-list-check-2')
                    ->modalHeading('Liquidation Report Activity Timeline')
                    ->modalContent(fn ($record) => view('components.timeline_views.activity_logs', [
                        'record' => $record,
                    ])),
                ViewAction::make('view')
                    ->label('Preview')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('signatory.liquidation-reports.show', ['liquidation_report' => $record]), true),
            ])->icon('ri-eye-line'),
        ];
    }

    public function render()
    {
        return view('livewire.offices.office-liquidation-reports-forwarded');
    }
}
