<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('office_id')->nullable()->after('user_id')->constrained('offices')->nullOnDelete();
            $table->foreignId('work_shift_id')->nullable()->after('office_id')->constrained('work_shifts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['work_shift_id']);
            $table->dropColumn(['office_id', 'work_shift_id']);
        });
    }
};
