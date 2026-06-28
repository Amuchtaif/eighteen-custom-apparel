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
        Schema::create('production_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->boolean('print_status')->default(false);
            $table->timestamp('print_at')->nullable();
            $table->boolean('press_status')->default(false);
            $table->timestamp('press_at')->nullable();
            $table->boolean('pola_status')->default(false);
            $table->timestamp('pola_at')->nullable();
            $table->boolean('jahit_status')->default(false);
            $table->timestamp('jahit_at')->nullable();
            $table->boolean('finish_status')->default(false);
            $table->timestamp('finish_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_progress');
    }
};
