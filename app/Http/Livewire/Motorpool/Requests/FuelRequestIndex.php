<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\FuelRequisition;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;

class FuelRequestIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return FuelRequisition::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
                ->label('Requested By')
                ->searchable()
                ->wrap(),
            Tables\Columns\TextColumn::make('slip_number')
                ->label('Slip Number')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('article')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('unit')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('purpose')
                ->wrap()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->label('Date Created'),
            Tables\Columns\BadgeColumn::make('is_liquidated')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state ? 'Completed' : 'Pending')
                ->colors([
                    'success' => fn ($state) => $state === true,
                    'warning' => fn ($state) => $state === false,
                ]),

        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make('print')
                ->label('Fuel Requisition Slip')
                ->icon('ri-printer-fill')
                ->button()
                ->color('success')
                ->openUrlInNewTab()
                ->url(fn ($record) => route('motorpool.request.fuel-request-slip', ['request' => $record]), true),

            // Edit Request button - to set requested unit price and total amount
            Action::make('edit_request')
                ->label('Edit Request')
                ->icon('ri-edit-line')
                ->button()
                ->color('primary')
                ->modalHeading('Edit Requested Values')
                ->modalWidth('2xl')
                ->form([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->required()
                                ->disabled()
                                ->dehydrated(false),
                            Forms\Components\TextInput::make('unit')
                                ->label('Unit')
                                ->required()
                                ->disabled()
                                ->dehydrated(false),
                            Forms\Components\TextInput::make('requested_unit_price')
                                ->label('Requested Unit Price')
                                ->numeric()
                                ->required()
                                ->reactive()
                                ->prefix('₱')
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $quantity = $get('quantity');
                                    if ($state && $quantity) {
                                        $set('requested_total_amount', $state * $quantity);
                                    }
                                }),
                            Forms\Components\TextInput::make('requested_total_amount')
                                ->label('Requested Total Amount')
                                ->numeric()
                                ->required()
                                ->prefix('₱')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                ])
                ->mountUsing(function ($form, $record) {
                    $form->fill([
                        'quantity' => $record->quantity,
                        'unit' => $record->unit,
                        'requested_unit_price' => $record->requested_unit_price,
                        'requested_total_amount' => $record->requested_total_amount,
                    ]);
                })
                ->action(function (array $data, $record) {
                    // Auto-calculate total amount
                    $data['requested_total_amount'] = $data['requested_unit_price'] * $record->quantity;

                    $record->update([
                        'requested_unit_price' => $data['requested_unit_price'],
                        'requested_total_amount' => $data['requested_total_amount'],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Request Updated')
                        ->body('Requested values have been updated.')
                        ->send();
                }),

            // Add Actual button (only show if NOT completed)
            Action::make('add_actual')
                ->label('Add Actual')
                ->icon('ri-file-list-3-line')
                ->button()
            ->color('primary')
                ->modalHeading('Add Actual Values')
                ->modalWidth('5xl')
                ->form([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Placeholder::make('requested_info')
                                ->label('REQUESTED VALUES')
                                ->columnSpan(2)
                                ->content(function ($record) {
                                    $unitPrice = $record->requested_unit_price ? '₱' . number_format($record->requested_unit_price, 2) : 'Not set';
                                    $totalAmount = $record->requested_total_amount ? '₱' . number_format($record->requested_total_amount, 2) : 'Not set';

                                    return new \Illuminate\Support\HtmlString("
                                        <div class='text-sm space-y-1 bg-gray-50 dark:bg-gray-800 p-3 rounded'>
                                            <div><strong>Quantity:</strong> {$record->quantity} {$record->unit}</div>
                                            <div><strong>Article:</strong> {$record->article}</div>
                                            <div><strong>Unit Price:</strong> {$unitPrice}</div>
                                            <div><strong>Total Amount:</strong> {$totalAmount}</div>
                                            <div class='pt-2 border-t'><strong>Purpose:</strong> {$record->purpose}</div>
                                        </div>
                                    ");
                                }),
                        ]),
                    Forms\Components\Section::make('Actual Values')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('actual_quantity')
                                        ->label('Actual Quantity')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->suffix(fn ($record) => $record->unit ?? 'Liters')
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $price = $get('actual_unit_price');
                                            if ($state && $price) {
                                                $set('actual_total_amount', $state * $price);
                                            }
                                        }),
                                    Forms\Components\TextInput::make('actual_unit_price')
                                        ->label('Actual Unit Price')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->prefix('₱')
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $quantity = $get('actual_quantity');
                                            if ($state && $quantity) {
                                                $set('actual_total_amount', $state * $quantity);
                                            }
                                        }),
                                    Forms\Components\TextInput::make('actual_total_amount')
                                        ->label('Actual Total Amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('₱')
                                        ->disabled()
                                        ->dehydrated(),
                                    Forms\Components\TextInput::make('actual_or_number')
                                        ->label('OR Number')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\DatePicker::make('actual_date')
                                        ->label('Date of Fueling')
                                        ->required()
                                        ->default(now()),
                                    Forms\Components\TimePicker::make('actual_time')
                                        ->label('Time of Fueling')
                                        ->required()
                                        ->default(now()),
                                    Forms\Components\TextInput::make('actual_supplier_attendant')
                                        ->label('Supplier/Attendant')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(2),
                                ]),
                        ]),
                ])
                ->action(function (array $data, $record) {
                    // Auto-calculate total amount
                    $data['actual_total_amount'] = $data['actual_quantity'] * $data['actual_unit_price'];
                    $data['is_liquidated'] = true;

                    $record->update($data);

                    Notification::make()
                        ->success()
                        ->title('Actual Values Saved')
                        ->body('Actual fuel data has been successfully saved.')
                        ->send();
                })
                ->visible(fn ($record) => !$record->is_liquidated),

            // View Actual button (only show if completed)
            Action::make('view_actual')
                ->label('View Actual')
                ->icon('ri-checkbox-circle-line')
                ->button()
                ->color('primary')
                ->modalHeading('Actual Values - Comparison')
                ->modalWidth('5xl')
                ->modalContent(fn ($record) => new \Illuminate\Support\HtmlString("
                    <div class='p-6'>
                        <div class='grid grid-cols-2 gap-6'>
                            <div class='bg-gray-100 p-4 rounded'>
                                <h3 class='font-bold mb-3'>REQUESTED</h3>
                                <div><strong>Quantity:</strong> {$record->quantity} {$record->unit}</div>
                                <div><strong>Article:</strong> {$record->article}</div>
                                <div><strong>Unit Price:</strong> ₱" . number_format($record->requested_unit_price ?? 0, 2) . "</div>
                                <div><strong>Total:</strong> ₱" . number_format($record->requested_total_amount ?? 0, 2) . "</div>
                            </div>
                            <div class='bg-green-100 p-4 rounded'>
                                <h3 class='font-bold mb-3'>ACTUAL</h3>
                                <div><strong>Quantity:</strong> {$record->actual_quantity} {$record->unit}</div>
                                <div><strong>Unit Price:</strong> ₱" . number_format($record->actual_unit_price, 2) . "</div>
                                <div><strong>Total:</strong> ₱" . number_format($record->actual_total_amount, 2) . "</div>
                                <div><strong>OR Number:</strong> {$record->actual_or_number}</div>
                                <div><strong>Date:</strong> {$record->actual_date}</div>
                                <div><strong>Time:</strong> {$record->actual_time}</div>
                                <div><strong>Attendant:</strong> {$record->actual_supplier_attendant}</div>
                            </div>
                        </div>
                    </div>
                "))
                ->action(fn () => null)
                ->modalButton('Close')
                ->visible(fn ($record) => $record->is_liquidated),
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.fuel-request-index');
    }
}
