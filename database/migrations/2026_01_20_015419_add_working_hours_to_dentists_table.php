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
        Schema::table('dentists', function (Blueprint $table) {
            $table->time('work_start_time')->default('08:00:00')->after('specialization');
            $table->time('work_end_time')->default('16:00:00')->after('work_start_time');
            $table->integer('slot_duration')->default(30)->after('work_end_time')->comment('Duration in minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dentists', function (Blueprint $table) {
            $table->dropColumn(['work_start_time', 'work_end_time', 'slot_duration']);
        });
    }
};
