<?php

namespace App\Console\Commands;

use App\Models\DisbursementVoucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-time backfill so already-encoded "Local Travel (Legacy)" (subtype 97) and
 * "Foreign Travel (Legacy)" (subtype 98) cash advances surface in the Cash
 * Advance Aging report and the FMR/FMD/SCO notice chain.
 *
 * Both features key off a CaReminderStep with a non-null liquidation_period_end_date.
 * Legacy-travel DVs that were issued (cheque set) before this feature either have
 * no reminder, or one with a null deadline. This command creates/repairs them.
 *
 * Idempotent: a reminder that already has a liquidation_period_end_date is left
 * untouched. Safe to re-run. Use --dry-run to preview.
 */
class BackfillLegacyTravelReminders extends Command
{
    protected $signature = 'cash-advance:backfill-legacy-travel {--dry-run : Report what would change without writing}';

    protected $description = 'Backfill CaReminderStep + liquidation deadline for legacy travel cash advances (subtypes 97/98)';

    /** Liquidation period (days after the travel end date) per legacy travel subtype. */
    private const DAYS_PER_SUBTYPE = [
        97 => 30, // Local Travel (Legacy)  — mirrors subtype 1
        98 => 60, // Foreign Travel (Legacy) — mirrors subtype 2
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $vouchers = DisbursementVoucher::query()
            ->whereIn('voucher_subtype_id', array_keys(self::DAYS_PER_SUBTYPE))
            ->whereNotNull('cheque_number')
            ->with(['travel_order', 'cash_advance_reminder'])
            ->get();

        $created = 0;
        $updated = 0;
        $skippedNoAnchor = 0;
        $skippedHasDeadline = 0;

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be written.');
        }

        DB::beginTransaction();

        try {
            foreach ($vouchers as $voucher) {
                $reminder = $voucher->cash_advance_reminder;

                // Already complete — nothing to do (keeps the command idempotent).
                if ($reminder && filled($reminder->liquidation_period_end_date)) {
                    $skippedHasDeadline++;
                    continue;
                }

                $endDate = $this->voucherEndDate($voucher);
                if (! $endDate) {
                    $this->line("DV {$voucher->dv_number} (#{$voucher->id}) — no usable anchor date, skipped.");
                    $skippedNoAnchor++;
                    continue;
                }

                $days = self::DAYS_PER_SUBTYPE[(int) $voucher->voucher_subtype_id];
                $liquidationPeriodEndDate = Carbon::parse($endDate)->addDays($days)->format('Y-m-d');

                if ($reminder) {
                    $this->line("DV {$voucher->dv_number} (#{$voucher->id}) — set deadline {$liquidationPeriodEndDate}.");
                    if (! $dryRun) {
                        $reminder->update([
                            'voucher_end_date' => $reminder->voucher_end_date ?? $endDate,
                            'liquidation_period_end_date' => $liquidationPeriodEndDate,
                        ]);
                    }
                    $updated++;
                } else {
                    $this->line("DV {$voucher->dv_number} (#{$voucher->id}) — create reminder, deadline {$liquidationPeriodEndDate}.");
                    if (! $dryRun) {
                        $voucher->cash_advance_reminder()->create([
                            'status' => 'On-Going',
                            'voucher_end_date' => $endDate,
                            'liquidation_period_end_date' => $liquidationPeriodEndDate,
                            'step' => 1,
                            'is_sent' => false,
                            'title' => 'Send FMR',
                            'message' => 'Ongoing liquidation of cash advance.',
                            'user_id' => $voucher->user_id,
                        ]);
                    }
                    $created++;
                }
            }

            if ($dryRun) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Backfill failed: '.$e->getMessage());

            return Command::FAILURE;
        }

        $this->info('Legacy travel reminder backfill complete.');
        $this->table(
            ['Reminders created', 'Deadlines set', 'Skipped (no anchor)', 'Skipped (already set)'],
            [[$created, $updated, $skippedNoAnchor, $skippedHasDeadline]],
        );

        return Command::SUCCESS;
    }

    /**
     * Anchor date the liquidation deadline is measured from, in priority order:
     * travel order end → activity end → cheque issue date → submission date.
     */
    private function voucherEndDate(DisbursementVoucher $voucher): ?string
    {
        $candidates = [
            $voucher->travel_order?->date_to,
            $voucher->other_details['activity_date_to'] ?? null,
            $voucher->cheque_number_added_at,
            $voucher->submitted_at,
        ];

        foreach ($candidates as $candidate) {
            if (filled($candidate)) {
                return Carbon::parse($candidate)->format('Y-m-d');
            }
        }

        return null;
    }
}
