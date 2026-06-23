<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE batch_transmittals DROP FOREIGN KEY batch_transmittals_office_group_id_foreign');
        DB::statement('ALTER TABLE batch_transmittals DROP INDEX batch_transmittals_office_group_id_serial_number_unique');
        DB::statement('ALTER TABLE batch_transmittals MODIFY serial_number VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE batch_transmittals ADD UNIQUE batch_transmittals_office_group_id_serial_number_unique (office_group_id, serial_number)');
        DB::statement('ALTER TABLE batch_transmittals ADD CONSTRAINT batch_transmittals_office_group_id_foreign FOREIGN KEY (office_group_id) REFERENCES office_groups(id)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE batch_transmittals DROP FOREIGN KEY batch_transmittals_office_group_id_foreign');
        DB::statement('ALTER TABLE batch_transmittals DROP INDEX batch_transmittals_office_group_id_serial_number_unique');
        DB::statement('ALTER TABLE batch_transmittals MODIFY serial_number INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE batch_transmittals ADD UNIQUE batch_transmittals_office_group_id_serial_number_unique (office_group_id, serial_number)');
        DB::statement('ALTER TABLE batch_transmittals ADD CONSTRAINT batch_transmittals_office_group_id_foreign FOREIGN KEY (office_group_id) REFERENCES office_groups(id)');
    }
};
