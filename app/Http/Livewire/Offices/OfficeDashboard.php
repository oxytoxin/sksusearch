<?php

namespace App\Http\Livewire\Offices;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Grid;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Models\LiquidationReport;

class OfficeDashboard extends Component
{

    public function mount()
    {
        if (!in_array(auth()->user()->employee_information?->office->office_group_id, [1, 2, 3, 4, 5])) {
            abort(403, 'You are not allowed to access this page.');
        }
    }
    public function render()
    {
        return view('livewire.offices.office-dashboard', [
            'disbursement_vouchers_count' => DisbursementVoucher::whereRelation('current_step', 'office_group_id', '=', auth()->user()->employee_information->office->office_group_id)->count(),
            'liquidation_reports_count' => LiquidationReport::whereRelation('current_step', 'office_group_id', '=', auth()->user()->employee_information->office->office_group_id)->count()
        ]);
    }
}
