<?php

namespace Tests\Feature;

use App\Models\RequestSchedule;
use App\Models\RequestScheduleTimeAndDate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Behavior / regression test for the motorpool vehicle-request conflict validation.
 *
 * Question under test: "When I pick a date, time, driver and vehicle, will the app actually
 * catch a double-booking of the SCHEDULE, the DRIVER, or the VEHICLE?"
 *
 * The conflict logic is copy-pasted across several methods in
 * app/Http/Livewire/Requisitioner/Motorpool/RequestVehicleShow.php and
 * app/Http/Livewire/Motorpool/Requests/RequestNewSchedule.php. Rather than boot
 * Livewire/Filament (those methods also dispatch SMS jobs, notifications and redirects),
 * this test mirrors each conflict query VERBATIM (with source citations) and runs it through
 * the real Eloquent models — the same approach DisbursementVoucherReturnFlowTest uses to
 * mirror the ->visible() closure.
 *
 * Hermetic: isolated sqlite :memory: DB with a minimal hand-rolled schema. Touches no real
 * data and changes no application code.
 *
 * Result summary (all reflect the CURRENT, fixed code):
 *   1. Vehicle overlap IS detected.
 *   2. Driver double-booking is prevented on Assign Driver           (confirmDriver gate).
 *   3. Back-to-back booking allowed consistently by both gates       (bounds equivalent).
 *   4. Driver clash is now caught by the vehicle-assign gate too      (confirmVehicle driver-aware).
 *   5. Multi-day approval is blocked when a later day conflicts       (no short-circuit).
 *   6. Multi-day vehicle assignment blocks the WHOLE request          (no partial commit).
 *   7. GSO direct-create detects a vehicle/driver conflict           (RequestNewSchedule gate).
 */
class VehicleRequestConflictTest extends TestCase
{
    /** IDs used as the "already booked" resources. */
    private const VEHICLE_A = 1;
    private const DRIVER_A = 1;

    protected function setUp(): void
    {
        parent::setUp();

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

        // Seed the incumbent booking: Vehicle A + Driver A, July 10 2026, 10:00-12:00, Approved.
        $this->seedApprovedTrip(self::VEHICLE_A, self::DRIVER_A, '2026-07-10', '10:00:00', '12:00:00');
    }

    /** SCENARIO 1 — Overlapping VEHICLE booking is caught. */
    public function test_vehicle_overlap_is_detected(): void
    {
        $conflict = $this->vehicleAssignConflict(self::VEHICLE_A, null, '2026-07-10', '11:00:00', '13:00:00');

        $this->assertNotNull($conflict, 'A vehicle booked over an overlapping time must be flagged.');
    }

    /** A vehicle trip on a different day is free. */
    public function test_vehicle_on_different_day_is_free(): void
    {
        $conflict = $this->vehicleAssignConflict(self::VEHICLE_A, null, '2026-07-11', '10:00:00', '12:00:00');

        $this->assertNull($conflict, 'A booking on another date must not be treated as a conflict.');
    }

    /**
     * SCENARIO 2 — Driver double-booking is prevented on the "Assign Driver" path.
     * The fixed confirmDriver() gates the driver before assigning.
     */
    public function test_driver_double_booking_is_prevented_on_assign(): void
    {
        $second = RequestSchedule::create([
            'request_type' => 0,
            'status' => 'Approved',
            'vehicle_id' => 2, // different vehicle, so ONLY the driver would clash
            'driver_id' => null,
            'purpose' => 'Overlapping trip needing a driver',
        ]);
        RequestScheduleTimeAndDate::create([
            'request_schedule_id' => $second->id,
            'vehicle_id' => 2,
            'travel_date' => '2026-07-10',
            'time_from' => '11:00:00',
            'time_to' => '13:00:00',
        ]);

        $blocked = $this->assignDriverConflict(self::DRIVER_A, '2026-07-10', '11:00:00', '13:00:00', $second->id);
        $this->assertNotNull($blocked, 'The Assign Driver path must detect the overlapping driver booking.');

        if (! $blocked) {
            $second->driver_id = self::DRIVER_A;
            $second->save();
        }

        $overlappingTripsForDriverA = RequestSchedule::where('status', 'Approved')
            ->where('driver_id', self::DRIVER_A)
            ->whereHas('date_and_times', function ($q) {
                $q->where('travel_date', '2026-07-10')
                    ->where('time_from', '<', '13:00:00')
                    ->where('time_to', '>', '10:00:00');
            })
            ->count();

        $this->assertSame(1, $overlappingTripsForDriverA, 'Driver A must not be double-booked.');
    }

    /**
     * SCENARIO 3 — Back-to-back booking is allowed CONSISTENTLY by both gates.
     * confirmVehicle() (half-open) and confirmApprove() (inclusive 3-case whereTime) are
     * logically equivalent on time overlap: a start equal to a prior end is FREE.
     */
    public function test_back_to_back_booking_is_allowed_by_both_gates(): void
    {
        $vehicleGate = $this->vehicleAssignConflict(self::VEHICLE_A, self::DRIVER_A, '2026-07-10', '12:00:00', '14:00:00');
        $approveGate = $this->approveConflict(self::VEHICLE_A, self::DRIVER_A, '2026-07-10', '12:00:00', '14:00:00');

        $this->assertNull($vehicleGate, 'confirmVehicle() treats a 12:00 start after a 12:00 end as free.');
        $this->assertNull($approveGate, 'confirmApprove() agrees — the back-to-back slot is free.');
    }

    /**
     * SCENARIO 4 — The vehicle-assign gate now catches a busy DRIVER too.
     * A new trip takes a FREE vehicle (B) but the ALREADY-BOOKED driver (A), overlapping.
     * The fixed confirmVehicle() ORs the driver in, so it is no longer blind to the clash.
     */
    public function test_vehicle_assign_gate_now_catches_busy_driver(): void
    {
        $freeVehicleB = 2;

        $conflict = $this->vehicleAssignConflict($freeVehicleB, self::DRIVER_A, '2026-07-10', '11:00:00', '13:00:00');

        $this->assertNotNull(
            $conflict,
            'confirmVehicle() must catch the driver double-booking even when the vehicle itself is free.'
        );
    }

    /**
     * SCENARIO 5 — Multi-day APPROVAL is blocked when a later day conflicts.
     * confirmApprove() now checks every day before approving (day 1 clear, day 2 collides).
     */
    public function test_multi_day_request_is_blocked_when_a_later_day_conflicts(): void
    {
        $request = RequestSchedule::create([
            'request_type' => 0,
            'status' => 'Pending',
            'vehicle_id' => self::VEHICLE_A,
            'driver_id' => self::DRIVER_A,
            'purpose' => 'Two-day trip; day 2 collides',
        ]);
        RequestScheduleTimeAndDate::create([
            'request_schedule_id' => $request->id,
            'travel_date' => '2026-07-11', // clear
            'time_from' => '10:00:00',
            'time_to' => '12:00:00',
        ]);
        RequestScheduleTimeAndDate::create([
            'request_schedule_id' => $request->id,
            'travel_date' => '2026-07-10', // collides with the incumbent
            'time_from' => '10:00:00',
            'time_to' => '12:00:00',
        ]);

        $outcome = $this->simulateConfirmApproveLoop($request);

        $this->assertSame('Conflict', $outcome, 'Day 2 conflicts, so the whole request must be rejected.');
    }

    /**
     * SCENARIO 6 — Multi-day VEHICLE assignment blocks the whole request (no partial commit).
     * confirmVehicle() now validates every day before writing anything. Day 1 is clear, day 2
     * collides; the assignment must be aborted and nothing saved.
     */
    public function test_vehicle_assignment_blocks_whole_request_when_a_later_day_conflicts(): void
    {
        $request = RequestSchedule::create([
            'request_type' => 0,
            'status' => 'Approved',
            'vehicle_id' => null,
            'driver_id' => null,
            'purpose' => 'Two-day trip; day 2 collides on the vehicle',
        ]);
        RequestScheduleTimeAndDate::create([
            'request_schedule_id' => $request->id,
            'travel_date' => '2026-07-11', // clear
            'time_from' => '10:00:00',
            'time_to' => '12:00:00',
        ]);
        RequestScheduleTimeAndDate::create([
            'request_schedule_id' => $request->id,
            'travel_date' => '2026-07-10', // collides with incumbent Vehicle A
            'time_from' => '10:00:00',
            'time_to' => '12:00:00',
        ]);

        $outcome = $this->simulateConfirmVehicleAssign($request->fresh(), self::VEHICLE_A, null);

        $this->assertSame('Blocked', $outcome, 'A later-day conflict must block the assignment.');
        $this->assertNull(
            $request->fresh()->vehicle_id,
            'No partial assignment: the vehicle must not be saved when any day conflicts.'
        );
    }

    /**
     * SCENARIO 7 — GSO direct-create (RequestNewSchedule) detects a vehicle/driver conflict.
     * Previously this path did no check at all. It now blocks an overlapping vehicle or driver
     * and allows a genuinely free slot.
     */
    public function test_new_schedule_creation_detects_conflicts(): void
    {
        $this->assertNotNull(
            $this->newScheduleConflict(self::VEHICLE_A, 99, '2026-07-10', '11:00:00', '13:00:00'),
            'A GSO booking on an overlapping VEHICLE must be blocked.'
        );

        $this->assertNotNull(
            $this->newScheduleConflict(999, self::DRIVER_A, '2026-07-10', '11:00:00', '13:00:00'),
            'A GSO booking on an overlapping DRIVER must be blocked.'
        );

        $this->assertNull(
            $this->newScheduleConflict(999, 99, '2026-07-12', '11:00:00', '13:00:00'),
            'A free vehicle and free driver on a free day must be allowed.'
        );
    }

    // ---------------------------------------------------------------------
    // Mirror helpers — copied verbatim from the application methods
    // ---------------------------------------------------------------------

    /** Mirror of the FIXED confirmVehicle() — RequestVehicleShow.php. Vehicle OR (driver, if set), self excluded. */
    private function vehicleAssignConflict(int $vehicleId, ?int $driverId, string $date, string $from, string $to, int $excludeRequestId = 0): ?RequestScheduleTimeAndDate
    {
        return RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId, $driverId) {
            $query->where('status', 'Approved')
                ->where(function ($q) use ($vehicleId, $driverId) {
                    $q->where('vehicle_id', $vehicleId);
                    if (! is_null($driverId)) {
                        $q->orWhere('driver_id', $driverId);
                    }
                });
        })
            ->where('travel_date', $date)
            ->where(function ($query) use ($from, $to) {
                $query->where(function ($q) use ($from, $to) {
                    $q->where('time_from', '<', $to)->where('time_to', '>', $from);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('time_from', '>=', $from)->where('time_to', '<=', $to);
                });
            })
            ->where('request_schedule_id', '!=', $excludeRequestId)
            ->first();
    }

    /** Mirror of the FIXED confirmDriver() — RequestVehicleShow.php. Driver, self excluded. */
    private function assignDriverConflict(int $driverId, string $date, string $from, string $to, int $excludeRequestId): ?RequestScheduleTimeAndDate
    {
        return RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($driverId) {
            $query->where('status', 'Approved')->where('driver_id', $driverId);
        })
            ->where('travel_date', $date)
            ->where(function ($query) use ($from, $to) {
                $query->where(function ($q) use ($from, $to) {
                    $q->where('time_from', '<', $to)->where('time_to', '>', $from);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('time_from', '>=', $from)->where('time_to', '<=', $to);
                });
            })
            ->where('request_schedule_id', '!=', $excludeRequestId)
            ->first();
    }

    /** Mirror of confirmApprove() — RequestVehicleShow.php. Inclusive 3-case whereTime, vehicle OR driver. */
    private function approveConflict(int $vehicleId, ?int $driverId, string $date, string $from, string $to): ?RequestScheduleTimeAndDate
    {
        return RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId, $driverId) {
            $query->where('status', 'Approved')
                ->where(function ($q) use ($vehicleId, $driverId) {
                    $q->where('vehicle_id', $vehicleId);
                    if (! is_null($driverId)) {
                        $q->orWhere('driver_id', $driverId);
                    }
                });
        })
            ->where('travel_date', $date)
            ->where(function ($q) use ($from, $to) {
                $q->where(function ($sub) use ($from) {
                    $sub->whereTime('time_from', '<=', $from)->whereTime('time_to', '>', $from);
                })->orWhere(function ($sub) use ($to) {
                    $sub->whereTime('time_from', '<', $to)->whereTime('time_to', '>=', $to);
                })->orWhere(function ($sub) use ($from, $to) {
                    $sub->whereTime('time_from', '>=', $from)->whereTime('time_to', '<=', $to);
                });
            })
            ->first();
    }

    /** Mirror of the FIXED RequestNewSchedule::save() conflict check. Vehicle OR driver. */
    private function newScheduleConflict(int $vehicleId, int $driverId, string $date, string $from, string $to): ?RequestScheduleTimeAndDate
    {
        return RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId, $driverId) {
            $query->where('status', 'Approved')
                ->where(function ($q) use ($vehicleId, $driverId) {
                    $q->where('vehicle_id', $vehicleId)->orWhere('driver_id', $driverId);
                });
        })
            ->where('travel_date', $date)
            ->where(function ($query) use ($from, $to) {
                $query->where(function ($q) use ($from, $to) {
                    $q->where('time_from', '<', $to)->where('time_to', '>', $from);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('time_from', '>=', $from)->where('time_to', '<=', $to);
                });
            })
            ->first();
    }

    /** Mirror of the FIXED confirmApprove() loop: check all days, approve only if all clear. */
    private function simulateConfirmApproveLoop(RequestSchedule $request): string
    {
        foreach ($request->date_and_times as $item) {
            if ($this->approveConflict($request->vehicle_id, $request->driver_id, $item->travel_date, $item->time_from, $item->time_to)) {
                return 'Conflict';
            }
        }

        $request->status = 'Approved';
        $request->save();

        return 'Approved';
    }

    /** Mirror of the FIXED confirmVehicle() loop: check all days, assign only if all clear. */
    private function simulateConfirmVehicleAssign(RequestSchedule $request, int $vehicleId, ?int $driverId): string
    {
        foreach ($request->date_and_times as $item) {
            if ($this->vehicleAssignConflict($vehicleId, $driverId, $item->travel_date, $item->time_from, $item->time_to, $request->id)) {
                return 'Blocked';
            }
        }

        $request->vehicle_id = $vehicleId;
        $request->save();
        foreach ($request->date_and_times as $item) {
            $item->vehicle_id = $vehicleId;
            $item->save();
        }

        return 'Assigned';
    }

    // ---------------------------------------------------------------------
    // Fixtures
    // ---------------------------------------------------------------------

    private function seedApprovedTrip(int $vehicleId, int $driverId, string $date, string $from, string $to): RequestSchedule
    {
        $request = RequestSchedule::create([
            'request_type' => 0,
            'status' => 'Approved',
            'vehicle_id' => $vehicleId,
            'driver_id' => $driverId,
            'purpose' => 'Incumbent booking',
        ]);

        RequestScheduleTimeAndDate::create([
            'request_schedule_id' => $request->id,
            'vehicle_id' => $vehicleId,
            'travel_date' => $date,
            'time_from' => $from,
            'time_to' => $to,
        ]);

        return $request;
    }

    private function buildSchema(): void
    {
        $schema = Schema::connection('sqlite');

        $schema->create('request_schedules', function ($table) {
            $table->id();
            $table->integer('request_type')->nullable();
            $table->unsignedBigInteger('travel_order_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->string('status')->nullable();
            $table->text('purpose')->nullable();
            $table->date('date_of_travel_from')->nullable();
            $table->date('date_of_travel_to')->nullable();
            $table->timestamps();
        });

        $schema->create('request_schedule_time_and_dates', function ($table) {
            $table->id();
            $table->unsignedBigInteger('request_schedule_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->date('travel_date');
            $table->time('time_from');
            $table->time('time_to');
            $table->timestamps();
        });
    }
}
