<?php

use Illuminate\Database\Migrations\Migration;

/**
 * RETIRED — intentionally a no-op.
 *
 * This migration originally seeded fund cluster "101" via firstOrCreate, but
 * `fund_clusters` is a shared, normalized reference table (FKs from
 * cost_centers/fund_allocations/mfo_fees/wfps) whose taxonomy should not be
 * auto-seeded by a report feature. "101" already exists in all environments,
 * so the original migration was a no-op.
 *
 * It already ran in production, so the file is kept (not deleted) and
 * neutralized to avoid migrate:status/rollback errors and to remove the
 * original down() that would have deleted the real "101" row.
 */
return new class extends Migration
{
    public function up(): void
    {
        // no-op (retired)
    }

    public function down(): void
    {
        // no-op (retired)
    }
};
