<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // For each batch transmittal item, find the matching "Forwarded to" activity log
        // on the DV and append the transmittal number if not already present.
        DB::table('batch_transmittal_items')
            ->join('batch_transmittals', 'batch_transmittals.id', '=', 'batch_transmittal_items.batch_transmittal_id')
            ->select([
                'batch_transmittal_items.disbursement_voucher_id',
                'batch_transmittals.serial_number',
                'batch_transmittals.forwarded_at',
                'batch_transmittals.created_at as batch_created_at',
            ])
            ->orderBy('batch_transmittal_items.id')
            ->chunk(500, function ($items) {
                foreach ($items as $item) {
                    $referenceTime = $item->forwarded_at ?? $item->batch_created_at;

                    if (!$referenceTime) {
                        continue;
                    }

                    // Find the "Forwarded to" activity log closest to the batch forwarded_at time
                    $log = DB::table('activity_logs')
                        ->where('loggable_type', 'dv')
                        ->where('loggable_id', $item->disbursement_voucher_id)
                        ->where('description', 'like', 'Forwarded to%')
                        ->where('description', 'not like', '%(Transmittal No.%')
                        ->whereBetween('created_at', [
                            date('Y-m-d H:i:s', strtotime($referenceTime) - 60),
                            date('Y-m-d H:i:s', strtotime($referenceTime) + 60),
                        ])
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($log) {
                        DB::table('activity_logs')
                            ->where('id', $log->id)
                            ->update([
                                'description' => $log->description . ' (Transmittal No. ' . $item->serial_number . ')',
                            ]);
                    }
                }
            });
    }
};
