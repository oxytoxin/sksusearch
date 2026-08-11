<?php

namespace Tests\Unit;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Illuminate\Validation\ValidationException;
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

            public function mapTableActionValidationException(ValidationException $exception): ValidationException
            {
                return $this->validationExceptionForMountedTableAction($exception);
            }
        };

        $mapped = $subject->mapTableActionValidationException($exception);

        $this->assertSame([
            'mountedTableActionData.mop_id' => [
                'Select a valid mode of payment.',
                'The selected mode is unavailable.',
            ],
            'mountedTableActionData.cheque_number' => [
                'The cheque number has already been taken.',
            ],
        ], $mapped->errors());
    }
}
