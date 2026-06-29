<?php

namespace App\Filament\Pages;

use App\Models\EmployeeInformation;
use App\Models\Office;
use App\Models\SmsDetailAccess;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class SmsDetailsAccess extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Testing Tools and Settings';

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static ?string $navigationLabel = 'SMS Details Access';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'sms-details-access';

    protected static string $view = 'filament.pages.sms-details-access';

    public $selectedUsers = [];
    public $selectedOffice = null;
    public $excludedUsers = [];

    protected function getFormSchema(): array
    {
        return [];
    }

    protected function getEmployeeFormSchema(): array
    {
        return [
            Select::make('selectedUsers')
                ->label('Employees')
                ->multiple()
                ->searchable()
                ->options(fn () => User::whereHas('employee_information')
                    ->whereDoesntHave('smsDetailAccess')
                    ->with('employee_information')
                    ->get()
                    ->sortBy('employee_information.full_name')
                    ->pluck('employee_information.full_name', 'id'))
                ->placeholder('Search employees...')
                ->reactive(),
        ];
    }

    protected function getOfficeFormSchema(): array
    {
        return [
            Select::make('selectedOffice')
                ->label('Office')
                ->searchable()
                ->options(fn () => Office::orderBy('name')->pluck('name', 'id'))
                ->placeholder('Search office...')
                ->reactive(),
            Select::make('excludedUsers')
                ->label('Exclude Employees (optional)')
                ->multiple()
                ->searchable()
                ->options(function () {
                    if (empty($this->selectedOffice)) {
                        return [];
                    }
                    return User::whereHas('employee_information', fn ($q) => $q->where('office_id', $this->selectedOffice))
                        ->whereDoesntHave('smsDetailAccess')
                        ->when(!empty($this->selectedUsers), fn ($q) => $q->whereNotIn('id', $this->selectedUsers))
                        ->with('employee_information')
                        ->get()
                        ->sortBy('employee_information.full_name')
                        ->pluck('employee_information.full_name', 'id');
                })
                ->placeholder('Search to exclude...')
                ->visible(fn () => !empty($this->selectedOffice)),
        ];
    }

    protected function getForms(): array
    {
        return [
            'employeeForm' => $this->makeForm()->schema($this->getEmployeeFormSchema()),
            'officeForm' => $this->makeForm()->schema($this->getOfficeFormSchema()),
        ];
    }

    public function addUsers()
    {
        if (empty($this->selectedUsers)) {
            Notification::make()->title('Please select at least one employee.')->warning()->send();
            return;
        }

        $added = 0;
        foreach ($this->selectedUsers as $userId) {
            if (!SmsDetailAccess::where('user_id', $userId)->exists()) {
                SmsDetailAccess::create(['user_id' => $userId]);
                $added++;
            }
        }

        if ($added > 0) {
            Notification::make()->title($added . ' employee(s) granted access.')->success()->send();
        } else {
            Notification::make()->title('Selected employees already have access.')->warning()->send();
        }

        $this->selectedUsers = [];
    }

    public function addOffice()
    {
        if (empty($this->selectedOffice)) {
            Notification::make()->title('Please select an office.')->warning()->send();
            return;
        }

        $users = User::whereHas('employee_information', fn ($q) => $q->where('office_id', $this->selectedOffice))
            ->whereDoesntHave('smsDetailAccess')
            ->when(!empty($this->excludedUsers), fn ($q) => $q->whereNotIn('id', $this->excludedUsers))
            ->pluck('id');

        $added = 0;
        foreach ($users as $userId) {
            SmsDetailAccess::create(['user_id' => $userId]);
            $added++;
        }

        if ($added > 0) {
            Notification::make()->title($added . ' employee(s) from office granted access.')->success()->send();
        } else {
            Notification::make()->title('All employees in this office already have access.')->warning()->send();
        }

        $this->selectedOffice = null;
        $this->excludedUsers = [];
    }

    protected function getTableQuery()
    {
        return SmsDetailAccess::query()->with('user.employee_information')->latest();
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('user.employee_information.full_name')
                ->label('Employee Name')
                ->searchable(),
            TextColumn::make('user.email')
                ->label('Email'),
            TextColumn::make('created_at')
                ->label('Date Added')
                ->dateTime('M d, Y h:i A')
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('remove')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Remove Access')
                ->modalSubheading('Are you sure you want to remove this employee\'s access to SMS Details?')
                ->action(fn ($record) => $record->delete()),
        ];
    }
}
