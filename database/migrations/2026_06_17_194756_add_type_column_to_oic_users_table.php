<?php

    use App\Enums\OicType;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('oic_users', function (Blueprint $table) {
                $table->string('type')->after('oic_id')->default(OicType::OIC->value);
            });
        }
    };
