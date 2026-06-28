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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_produksi')->default('Antrian')->change();
            $table->string('source')->default('Backoffice')->after('status_produksi');
            $table->string('jenis_apparel')->nullable()->after('source');
            $table->date('target_penggunaan')->nullable()->after('jenis_apparel');
            $table->decimal('budget_perkiraan', 15, 2)->nullable()->after('target_penggunaan');
        });

        Schema::create('order_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // e.g. logo, design_reference, color_reference, supporting_file
            $table->bigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_attachments');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['source', 'jenis_apparel', 'target_penggunaan', 'budget_perkiraan']);
            // Note: status_produksi can remain string or change back to enum if desired
        });
    }
};
