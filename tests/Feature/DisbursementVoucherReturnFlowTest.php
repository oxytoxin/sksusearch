<?php

namespace Tests\Feature;

use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use App\Models\RelatedDocumentsList;
use App\Models\User;
use App\Models\VoucherSubType;
use App\Models\VoucherType;
use App\Services\DisbursementVouchers\DisbursementVoucherWorkflowService;
use Database\Seeders\DisbursementVoucherStepSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Reproduction test for the reported issue:
 *
 *   "A pre-audit officer returns a DV to the requisitioner BEFORE verifying the
 *    related documents. The requisitioner forwards it back. Instead of the
 *    'Verify Related Documents' button, a 'Forward' button shows."
 *
 * These tests drive the real DisbursementVoucherWorkflowService through the exact
 * reported flow and assert the button-gating predicates that back the Filament
 * ->visible() closures:
 *   - Verify shows at 6000  <=> current_step_id == 6000 && subtype->related_documents_list
 *                                && !hasCompletedRelatedDocumentsVerification() && blank(pending_return_step_id)
 *                                (OfficeDashboardActions.php:309)
 *   - Forward shows         <=> DisbursementVoucherWorkflowService::canBeForwarded()
 *                                (OfficeDashboardActions.php:722 -> forwardBlocker():465)
 *
 * The test is hermetic: it runs on an isolated sqlite :memory: database with a
 * minimal hand-rolled schema (the app's full migration set cannot run on sqlite
 * because disbursement_voucher_particulars.final_amount is a MySQL-only generated
 * column). The DV step ladder itself is seeded from the real
 * DisbursementVoucherStepSeeder so the domain data under test is authentic.
 */
class DisbursementVoucherReturnFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Point everything at an isolated in-memory sqlite DB for this test class only.
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]);
        config()->set('database.default', 'sqlite');
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        $this->buildSchema();

        // Seed the real DV step ladder (steps 1000..25000).
        (new DisbursementVoucherStepSeeder())->run();
    }

    /**
     * The reported scenario. Prove the DV comes back needing "Verify" (not "Forward").
     */
    public function test_returned_dv_comes_back_to_verify_not_forward(): void
    {
        $service = app(DisbursementVoucherWorkflowService::class);

        [$dv, $requisitioner, $preAudit] = $this->makeVoucherAtPreAudit(withChecklist: true);

        // 1. Pre-audit returns to the requisitioner (step 1000) BEFORE verifying.
        $service->returnToStep($dv, 1000, 'Missing attachments.', ['actor' => $preAudit]);
        $dv->refresh();
        $this->assertEquals(1000, $dv->pending_return_step_id, 'Return should be pending to the requisitioner.');
        $this->assertEquals(6000, $dv->current_step_id, 'returnToStep must not move the DV.');

        // 2. Pre-audit releases the hardcopy: DV physically moves to the requisitioner.
        $service->releaseReturn($dv, $preAudit, 'LOG-001');
        $dv->refresh();
        $this->assertEquals(1000, $dv->current_step_id, 'DV should now sit with the requisitioner (1000).');
        $this->assertEquals(5000, $dv->previous_step_id, 'Resume pointer should remember pre-audit (5000).');
        $this->assertNull($dv->pending_return_step_id, 'Pending return should be cleared after release.');

        // 3. Requisitioner receives (1000 -> 2000).
        $service->receive($dv, $requisitioner);
        $dv->refresh();
        $this->assertEquals(2000, $dv->current_step_id);

        // 4. Requisitioner forwards it back. forward() jumps to previous_step_id (5000).
        $service->forward($dv, $requisitioner);
        $dv->refresh();
        $this->assertEquals(5000, $dv->current_step_id, 'Forwarding a returned DV should route it back to pre-audit (5000).');

        // 5. Pre-audit receives it again (5000 -> 6000).
        $service->receive($dv, $preAudit);
        $dv->refresh();
        $this->assertEquals(6000, $dv->current_step_id, 'DV should be back at the pre-audit verification step.');

        // 6. THE ASSERTIONS THAT MATTER: Verify shows, Forward is hidden.
        $this->assertFalse(
            $dv->hasCompletedRelatedDocumentsVerification(),
            'Documents were never verified, so verification must still be pending.'
        );
        $this->assertNotNull($dv->voucher_subtype->related_documents_list, 'The subtype has a checklist.');

        $this->assertFalse(
            $service->canBeForwarded($dv),
            'BUG CHECK: Forward must be HIDDEN at pre-audit until documents are verified.'
        );
        $this->assertTrue(
            $this->verifyButtonVisible($dv),
            'BUG CHECK: the "Verify Related Documents" button must be visible after the round-trip.'
        );
    }

    /**
     * The one legitimate case where "Forward" (not "Verify") shows at step 6000:
     * the voucher subtype has no related-documents checklist configured, so there is
     * nothing to verify. This is by design and independent of any return.
     */
    public function test_subtype_without_checklist_shows_forward_at_preaudit(): void
    {
        $service = app(DisbursementVoucherWorkflowService::class);

        [$dv] = $this->makeVoucherAtPreAudit(withChecklist: false);

        $this->assertNull($dv->voucher_subtype->related_documents_list, 'No checklist configured for this subtype.');
        $this->assertTrue(
            $service->canBeForwarded($dv),
            'With no checklist there is nothing to verify, so Forward shows by design.'
        );
        $this->assertFalse(
            $this->verifyButtonVisible($dv),
            'The Verify button should be hidden when the subtype has no checklist.'
        );
    }

    /**
     * Mirror of the Filament ->visible() closure for the "Verify Related Documents"
     * button (OfficeDashboardActions.php:309).
     */
    private function verifyButtonVisible(DisbursementVoucher $dv): bool
    {
        return $dv->current_step_id == 6000
            && $dv->for_cancellation == false
            && (bool) $dv->voucher_subtype?->related_documents_list
            && ! $dv->hasCompletedRelatedDocumentsVerification()
            && blank($dv->pending_return_step_id);
    }

    /**
     * @return array{0: DisbursementVoucher, 1: User, 2: User}
     */
    private function makeVoucherAtPreAudit(bool $withChecklist): array
    {
        $requisitioner = $this->makeUser('Requi Sitioner');
        $signatory = $this->makeUser('Sig Natory');
        $preAudit = $this->makeUser('Pre Audit');

        $type = VoucherType::create(['name' => 'Cash Advance']);
        $subtype = VoucherSubType::create(['name' => 'Local Travel', 'voucher_type_id' => $type->id]);

        if ($withChecklist) {
            RelatedDocumentsList::create([
                'voucher_sub_type_id' => $subtype->id,
                'documents' => ['Official Receipt', 'Certificate of Appearance'],
            ]);
        }

        $dv = DisbursementVoucher::create([
            'voucher_subtype_id' => $subtype->id,
            'user_id' => $requisitioner->id,
            'signatory_id' => $signatory->id,
            'tracking_number' => DisbursementVoucher::generateTrackingNumber(),
            'payee' => 'Juan Dela Cruz',
            'gross_amount' => 1000,
            'for_cancellation' => false,
            'certified_by_accountant' => false,
            'related_documents' => null,
            'current_step_id' => 6000,
            'previous_step_id' => 2000,
            'pending_return_step_id' => null,
        ]);

        return [$dv->fresh(), $requisitioner, $preAudit];
    }

    private function makeUser(string $fullName): User
    {
        $user = User::create([
            'name' => $fullName,
            'email' => str()->slug($fullName).'@example.test',
            'password' => bcrypt('password'),
        ]);

        EmployeeInformation::create([
            'user_id' => $user->id,
            'full_name' => $fullName,
        ]);

        return $user->fresh();
    }

    private function buildSchema(): void
    {
        $schema = Schema::connection('sqlite');

        $schema->create('users', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        $schema->create('employee_information', function ($table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('full_name');
            $table->foreignId('position_id')->nullable();
            $table->foreignId('office_id')->nullable();
            $table->timestamps();
        });

        $schema->create('voucher_types', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        $schema->create('voucher_sub_types', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('voucher_type_id')->nullable();
            $table->timestamps();
        });

        $schema->create('related_documents_lists', function ($table) {
            $table->id();
            $table->foreignId('voucher_sub_type_id')->nullable();
            $table->text('documents')->nullable();
            $table->text('liquidation_report_documents')->nullable();
            $table->timestamps();
        });

        $schema->create('disbursement_voucher_steps', function ($table) {
            $table->id();
            $table->string('process');
            $table->string('recipient')->nullable();
            $table->string('sender')->nullable();
            $table->foreignId('office_id')->nullable();
            $table->foreignId('office_group_id')->nullable();
            $table->unsignedBigInteger('return_step_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        $schema->create('disbursement_vouchers', function ($table) {
            $table->id();
            $table->foreignId('voucher_subtype_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('signatory_id')->nullable();
            $table->foreignId('mop_id')->nullable();
            $table->foreignId('travel_order_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('payee')->nullable();
            $table->boolean('certified_by_accountant')->default(false);
            $table->boolean('for_cancellation')->default(false);
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_remarks')->nullable();
            $table->text('related_documents')->nullable();
            $table->string('log_number')->nullable();
            $table->dateTime('documents_verified_at')->nullable();
            $table->string('ors_burs')->nullable();
            $table->string('dv_number')->nullable();
            $table->date('journal_date')->nullable();
            $table->string('cheque_number')->nullable();
            $table->foreignId('fund_cluster_id')->nullable();
            $table->date('submitted_at')->nullable();
            $table->text('other_details')->nullable();
            $table->integer('gross_amount')->nullable();
            $table->unsignedBigInteger('current_step_id')->nullable();
            $table->unsignedBigInteger('previous_step_id')->nullable();
            $table->unsignedBigInteger('pending_return_step_id')->nullable();
            $table->timestamps();
        });

        $schema->create('activity_logs', function ($table) {
            $table->id();
            $table->string('loggable_type')->nullable();
            $table->unsignedBigInteger('loggable_id')->nullable();
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
}
