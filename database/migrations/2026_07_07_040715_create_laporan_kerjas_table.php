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
        Schema::create('laporan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id_eksternal');
            $table->string('nip')->nullable();
            $table->string('nama_pegawai');
            $table->string('departemen')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('keterangan');
            $table->string('foto');
            $table->dateTime('waktu_lapor');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('employee_id_eksternal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerjas');
    }
};
