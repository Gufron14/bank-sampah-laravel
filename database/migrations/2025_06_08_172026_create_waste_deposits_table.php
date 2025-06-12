<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('waste_items'); // Menyimpan array jenis sampah, berat, harga, total
            $table->decimal('total_weight', 8, 2); // Total berat keseluruhan
            $table->decimal('total_amount', 15, 2); // Total pendapatan
            $table->enum('status', ['pending', 'picked_up', 'processed', 'completed'])->default('pending');
            $table->date('pickup_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_deposits');
    }
};
