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
        // 1. Drop foreign keys on order_players first
        if (Schema::hasTable('order_players')) {
            try {
                Schema::table('order_players', function (Blueprint $table) {
                    $table->dropForeign(['order_item_id']);
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('order_players', function (Blueprint $table) {
                    $table->dropColumn('order_item_id');
                });
            } catch (\Throwable $e) {}
        }

        // 2. Drop foreign keys on orders table first to allow dropping quotations, etc.
        if (Schema::hasTable('orders')) {
            $foreigns = ['quotation_id', 'designer_id', 'operator_print_id', 'penjahit_id', 'qc_id', 'material_id'];
            foreach ($foreigns as $foreign) {
                try {
                    Schema::table('orders', function (Blueprint $table) use ($foreign) {
                        $table->dropForeign([$foreign]);
                    });
                } catch (\Throwable $e) {}
            }
        }

        // 3. Drop child tables first
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('production_progress_histories');
        Schema::dropIfExists('production_progress');

        // 4. Drop parent tables
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');

        // 5. Drop columns on orders table
        if (Schema::hasTable('orders')) {
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn([
                        'quotation_id',
                        'designer_id',
                        'operator_print_id',
                        'penjahit_id',
                        'qc_id',
                        'material_id',
                        'lokasi_produksi',
                        'estimasi_selesai',
                        'subtotal',
                        'discount',
                        'grand_total',
                        'status',
                        'qty',
                        'paket'
                    ]);
                });
            } catch (\Throwable $e) {}
        }

        // 6. Add new columns to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('bahan')->nullable()->after('nama_team');
            $table->integer('qty_esti')->default(0)->after('bahan');
            $table->integer('qty_tink')->default(0)->after('qty_esti');
            $table->foreignId('package_id')->nullable()->after('qty_tink')->constrained('packages')->nullOnDelete();
            $table->decimal('total_tagihan', 15, 2)->default(0.00)->after('package_id');
            $table->decimal('dp', 15, 2)->default(0.00)->after('total_tagihan');
            $table->decimal('sisa_tagihan', 15, 2)->default(0.00)->after('dp');
            $table->enum('status_pembayaran', ['Belum DP', 'DP', 'Lunas'])->default('Belum DP')->after('sisa_tagihan');
            $table->enum('status_produksi', ['Antrian', 'Print', 'Press', 'Pola', 'Jahit', 'Finish', 'Siap Kirim', 'Selesai'])->default('Antrian')->after('status_pembayaran');
        });

        // 7. Connect order_players to orders directly
        Schema::table('order_players', function (Blueprint $table) {
            $table->foreignId('order_id')->after('id')->constrained('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('order_players', function (Blueprint $table) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            });
        } catch (\Throwable $e) {}
    }
};
