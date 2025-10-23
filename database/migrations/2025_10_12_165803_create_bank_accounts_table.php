<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bank_id')->constrained('banks');
                $table->string('number');
                $table->decimal('balance', 16, 2)->default(0);
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('bank_accounts');
        }
    };
