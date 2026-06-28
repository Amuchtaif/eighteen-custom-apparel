<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['check_in', 'check_out', 'latitude', 'longitude', 'selfie_photo']);

            // Add new columns
            $table->foreignId('office_id')->nullable()->after('employee_id')->constrained('offices')->nullOnDelete();
            $table->foreignId('work_shift_id')->nullable()->after('office_id')->constrained('work_shifts')->nullOnDelete();

            $table->dateTime('check_in_time')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->integer('check_in_distance')->nullable();
            $table->string('check_in_photo')->nullable();

            $table->dateTime('check_out_time')->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            $table->integer('check_out_distance')->nullable();
            $table->string('check_out_photo')->nullable();

            $table->string('attendance_status')->default('Hadir');

            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('device_info')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['work_shift_id']);
            $table->dropColumn([
                'office_id', 'work_shift_id',
                'check_in_time', 'check_in_latitude', 'check_in_longitude', 'check_in_distance', 'check_in_photo',
                'check_out_time', 'check_out_latitude', 'check_out_longitude', 'check_out_distance', 'check_out_photo',
                'attendance_status', 'ip_address', 'user_agent', 'device_info'
            ]);

            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('selfie_photo')->nullable();
        });
    }
};
