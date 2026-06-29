<?php

namespace App\Http\Livewire;

use App\Models\SmsDetailAccess;
use App\Models\SmsLog;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsDetails extends Component implements HasTable
{
    use InteractsWithTable;

    public $account = null;
    public $accountError = null;
    public $smsSentToday = 0;
    public $smsSentTotal = 0;
    public $filterContext = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public function mount()
    {
        abort_unless(SmsDetailAccess::where('user_id', auth()->id())->exists(), 403);

        $this->loadAccountDetails();
        $this->smsSentToday = SmsLog::whereDate('created_at', today())->count();
        $this->smsSentTotal = SmsLog::count();
    }

    public function loadAccountDetails()
    {
        try {
            $apiKey = config('services.semaphore.api_key');

            if (empty($apiKey)) {
                $this->accountError = 'Semaphore API key is not configured.';
                return;
            }

            $cached = Cache::get('semaphore_account_details');

            if ($cached) {
                $this->account = $cached;
                return;
            }

            $response = Http::timeout(15)->get('https://api.semaphore.co/api/v4/account', [
                'apikey' => $apiKey,
            ]);

            if ($response->successful()) {
                $this->account = $response->json();
                Cache::put('semaphore_account_details', $this->account, now()->addMinutes(5));
            } else {
                $this->accountError = 'Failed to fetch account details (HTTP ' . $response->status() . ')';
            }
        } catch (\Exception $e) {
            Log::error('SMS Details - Failed to load account: ' . $e->getMessage());
            $this->accountError = 'Could not connect to Semaphore API.';
        }
    }

    protected function getTableQuery()
    {
        return SmsLog::query()
            ->when($this->filterContext, fn (Builder $q, $context) => $q->where('context', $context))
            ->when($this->filterDateFrom, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($this->filterDateTo, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('created_at')
                ->label('Date Sent')
                ->dateTime('M d, Y h:i A')
                ->sortable(),
            Tables\Columns\TextColumn::make('user.employee_information.full_name')
                ->label('Recipient')
                ->formatStateUsing(function ($state, $record) {
                    $name = $state ?? $record->formatted_phone_number;
                    return $state ? $name . ' (' . $record->formatted_phone_number . ')' : $name;
                })
                ->searchable(['formatted_phone_number']),
            Tables\Columns\TextColumn::make('message')
                ->label('Message')
                ->wrap()
                ->searchable(),
            Tables\Columns\TextColumn::make('context')
                ->label('Context')
                ->formatStateUsing(fn ($state) => self::contextLabels()[$state] ?? $state)
                ->searchable(),
        ];
    }

    public static function contextLabels(): array
    {
        return [
            'travel_order_signatory_notification' => 'Travel Order - Signatory Notification',
            'travel_order_approved' => 'Travel Order - Approved',
            'vehicle_driver_confirmed' => 'Vehicle - Driver Confirmed',
            'vehicle_changed' => 'Vehicle - Changed',
            'driver_changed' => 'Vehicle - Driver Changed',
            'disbursement_voucher_returned' => 'Disbursement Voucher - Returned',
            'ENDORSEMENT' => 'Endorsement',
            'ENDORSEMENT_AUDITOR' => 'Endorsement - Auditor',
            'ENDORSEMENT_PAYEE' => 'Endorsement - Payee',
            'FD' => 'For Disbursement',
            'FMD' => 'For Mass Disbursement',
            'SCO' => 'Signed Check-Out',
            'FUND_ALLOCATION' => 'Fund Allocation',
            'FUND_ALLOCATION_161' => 'Fund Allocation (161)',
            'WFP_APPROVAL' => 'WFP - Approval',
            'WFP_APPROVAL_Q1' => 'WFP - Approval (Q1)',
            'WFP_MODIFICATION' => 'WFP - Modification',
            'WFP_MODIFICATION_Q1' => 'WFP - Modification (Q1)',
        ];
    }

    protected function getTableDefaultSort(): ?string
    {
        return 'created_at';
    }

    protected function getTableDefaultSortDirection(): ?string
    {
        return 'desc';
    }

    public function render()
    {
        return view('livewire.sms-details');
    }
}
