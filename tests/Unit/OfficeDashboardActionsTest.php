<?php

namespace Tests\Unit;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

class OfficeDashboardActionsTest extends TestCase
{
    public function test_table_action_validation_errors_are_mapped_to_the_filament_form_state(): void
    {
        $exception = ValidationException::withMessages([
            'mop_id' => [
                'Select a valid mode of payment.',
                'The selected mode is unavailable.',
            ],
            'mountedTableActionData.cheque_number' => 'The cheque number has already been taken.',
        ]);

        $subject = new class
        {
            use OfficeDashboardActions;

            public function mapTableActionValidationErrors(ValidationException $exception): array
            {
                return $this->mountedTableActionValidationErrors($exception);
            }
        };

        $mapped = $subject->mapTableActionValidationErrors($exception);

        $this->assertSame([
            'mountedTableActionData.mop_id' => [
                'Select a valid mode of payment.',
                'The selected mode is unavailable.',
            ],
            'mountedTableActionData.cheque_number' => [
                'The cheque number has already been taken.',
            ],
        ], $mapped);
    }

    public function test_table_action_validation_errors_are_added_before_the_action_is_halted(): void
    {
        $exception = ValidationException::withMessages([
            'bank_account_id' => 'The selected bank account has insufficient balance.',
            'voucher' => 'Cheque/ADA can only be recorded at the Cashier receiving step.',
        ]);

        $subject = new class
        {
            use OfficeDashboardActions {
                haltMountedTableActionForValidation as public;
            }

            public array $errors = [];

            public function addError($field, $message): void
            {
                $this->errors[$field][] = $message;
            }
        };

        try {
            $subject->haltMountedTableActionForValidation($exception);
            $this->fail('The table action was not halted after validation failed.');
        } catch (Halt) {
            // The modal remains mounted so Filament can render the validation state.
        }

        $this->assertSame([
            'mountedTableActionData.bank_account_id' => [
                'The selected bank account has insufficient balance.',
            ],
            'mountedTableActionData.voucher' => [
                'Cheque/ADA can only be recorded at the Cashier receiving step.',
            ],
        ], $subject->errors);

        $notification = collect(session('filament.notifications'))->last();
        $this->assertSame("The selected bank account has insufficient balance.\nCheque/ADA can only be recorded at the Cashier receiving step.", $notification['body']);
        $this->assertSame('persistent', $notification['duration']);
        Notification::assertNotified('Cheque/ADA could not be issued.');
    }

    public function test_table_action_validation_error_is_rendered_by_livewire(): void
    {
        Livewire::test(CashierValidationTestComponent::class)
            ->call('failChequeAda')
            ->assertHasErrors(['mountedTableActionData.bank_account_id'])
            ->assertSee('The selected bank account has insufficient balance.');
    }
}

class CashierValidationTestComponent extends Component
{
    use OfficeDashboardActions;

    public array $mountedTableActionData = [];

    public function failChequeAda(): void
    {
        try {
            $this->haltMountedTableActionForValidation(ValidationException::withMessages([
                'bank_account_id' => 'The selected bank account has insufficient balance.',
            ]));
        } catch (Halt) {
        }
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                @error('mountedTableActionData.bank_account_id')
                    <span>{{ $message }}</span>
                @enderror
            </div>
            BLADE;
    }
}
