<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bukti')->unique();
            $table->string('sudah_terima_dari');
            $table->bigInteger('jumlah');
            $table->string('terbilang');
            $table->string('untuk_pembayaran');
            $table->string('sumber_dana')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
