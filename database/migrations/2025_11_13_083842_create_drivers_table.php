<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->comment('Полное имя водителя');

            $table->string('phone', 20)
                ->nullable()
                ->comment('Контактный телефон водителя');

            $table->string('license_number')
                ->nullable()
                ->comment('Номер водительского удостоверения (при наличии)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
