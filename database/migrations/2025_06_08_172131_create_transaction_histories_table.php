<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal']); // Jenis transaksi
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('amount', 15, 2); // Nominal
            $table->decimal('balance_before', 15, 2)->default(0); // Saldo sebelum transaksi
            $table->decimal('balance_after', 15, 2)->default(0); // Saldo setelah transaksi
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_histories');
    }
};
