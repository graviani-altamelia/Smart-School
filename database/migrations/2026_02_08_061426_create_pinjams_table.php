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
        Schema::create('pinjams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');            
            $table->text('judul')->nullable();
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable();
            $table->integer('jumlah_pinjam')->default(1);
            $table->enum('status_peminjaman', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjams');
    }
};
