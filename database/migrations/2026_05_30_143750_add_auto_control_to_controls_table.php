<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('controls', function (Blueprint $table) {

            $table->string('system_mode')
                ->default('manual');

            $table->integer('fan_pwm')
                ->default(0);

            $table->integer('heater_pwm')
                ->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('controls', function (Blueprint $table) {

            $table->dropColumn([
                'system_mode',
                'fan_pwm',
                'heater_pwm'
            ]);

        });
    }
};