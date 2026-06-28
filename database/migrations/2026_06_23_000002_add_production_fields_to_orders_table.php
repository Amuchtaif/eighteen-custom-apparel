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
            // Rename columns
            $table->renameColumn('order_number', 'kode_order');
            $table->renameColumn('order_date', 'tanggal_masuk');
            $table->renameColumn('notes', 'catatan');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Add production fields
            $table->string('nama_team')->nullable()->after('customer_id');
            $table->date('estimasi_selesai')->nullable()->after('deadline');
            $table->enum('paket', ['Reguler', 'Premium', 'Express'])->default('Reguler')->after('estimasi_selesai');
            $table->integer('qty')->default(0)->after('paket');
            $table->enum('lokasi_produksi', ['Kantor Produksi', 'Studio Desain'])->nullable()->after('qty');
            
            $table->foreignId('designer_id')->nullable()->after('lokasi_produksi')->constrained('users')->nullOnDelete();
            $table->foreignId('operator_print_id')->nullable()->after('designer_id')->constrained('users')->nullOnDelete();
            $table->foreignId('penjahit_id')->nullable()->after('operator_print_id')->constrained('users')->nullOnDelete();
            $table->foreignId('qc_id')->nullable()->after('penjahit_id')->constrained('users')->nullOnDelete();
            $table->foreignId('material_id')->nullable()->after('qc_id')->constrained('materials')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign keys and columns
            $table->dropForeign(['designer_id']);
            $table->dropForeign(['operator_print_id']);
            $table->dropForeign(['penjahit_id']);
            $table->dropForeign(['qc_id']);
            $table->dropForeign(['material_id']);

            $table->dropColumn([
                'nama_team',
                'estimasi_selesai',
                'paket',
                'qty',
                'lokasi_produksi',
                'designer_id',
                'operator_print_id',
                'penjahit_id',
                'qc_id',
                'material_id',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            // Rename back columns
            $table->renameColumn('kode_order', 'order_number');
            $table->renameColumn('tanggal_masuk', 'order_date');
            $table->renameColumn('catatan', 'notes');
        });
    }
};
