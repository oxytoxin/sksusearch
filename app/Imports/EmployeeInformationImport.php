<?php

namespace App\Imports;

use App\Models\Campus;
use App\Models\EmployeeInformation;
use App\Models\Office;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeInformationImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function collection(Collection $rows)
    {
        // Build lookup maps from name -> id
        $campusMap = Campus::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [strtoupper($name) => $id]);
        $positionMap = Position::pluck('id', 'description')->mapWithKeys(fn ($id, $desc) => [strtoupper($desc) => $id]);

        // Office map: "Office Name (Campus Name)" -> id
        $officeMap = Office::with('campus')->get()->mapWithKeys(function ($office) {
            $key = strtoupper($office->name . ' (' . ($office->campus->name ?? '') . ')');
            return [$key => $office->id];
        });

        // Also map plain office name for simpler input
        $officePlainMap = Office::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [strtoupper($name) => $id]);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // account for header row

            $firstName = trim($row['first_name'] ?? '');
            $lastName = trim($row['last_name'] ?? '');
            $fullName = trim($row['full_name'] ?? '');
            $email = trim($row['email'] ?? '');

            // Skip empty rows
            if (empty($firstName) && empty($lastName) && empty($fullName) && empty($email)) {
                continue;
            }

            // Validate required fields
            if (empty($firstName) || empty($lastName) || empty($fullName) || empty($email)) {
                $this->errors[] = "Row {$rowNumber}: Missing required field (first_name, last_name, full_name, or email).";
                $this->skipped++;
                continue;
            }

            // Resolve campus
            $campusValue = strtoupper(trim($row['campus'] ?? ''));
            $campusId = $campusMap[$campusValue] ?? null;
            if ($campusValue && !$campusId) {
                $this->errors[] = "Row {$rowNumber}: Unknown campus '{$row['campus']}'.";
                $this->skipped++;
                continue;
            }

            // Resolve office (try "Name (Campus)" format first, then plain name)
            $officeValue = strtoupper(trim($row['office'] ?? ''));
            $officeId = $officeMap[$officeValue] ?? $officePlainMap[$officeValue] ?? null;
            if ($officeValue && !$officeId) {
                $this->errors[] = "Row {$rowNumber}: Unknown office '{$row['office']}'.";
                $this->skipped++;
                continue;
            }

            // Resolve position
            $positionValue = strtoupper(trim($row['position'] ?? ''));
            $positionId = $positionMap[$positionValue] ?? null;
            if ($positionValue && !$positionId) {
                $this->errors[] = "Row {$rowNumber}: Unknown position '{$row['position']}'.";
                $this->skipped++;
                continue;
            }

            // Skip if email already exists
            if (User::where('email', $email)->exists()) {
                $this->errors[] = "Row {$rowNumber}: Email '{$email}' already exists in the system.";
                $this->skipped++;
                continue;
            }

            DB::transaction(function () use ($firstName, $lastName, $fullName, $email, $campusId, $officeId, $positionId, $row) {
                $user = User::create([
                    'email' => $email,
                    'password' => Hash::make(strtolower(str_replace(' ', '', $lastName . '123'))),
                ]);

                $user->employee_information()->create([
                    'first_name' => strtoupper($firstName),
                    'last_name' => strtoupper($lastName),
                    'full_name' => strtoupper($fullName),
                    'address' => $row['address'] ?? null,
                    'contact_number' => $row['contact_number'] ?? null,
                    'birthday' => $row['birthday'] ?? null,
                    'user_id' => $user->id,
                    'position_id' => $positionId,
                    'office_id' => $officeId,
                    'campus_id' => $campusId,
                ]);
            });

            $this->imported++;
        }
    }
}
