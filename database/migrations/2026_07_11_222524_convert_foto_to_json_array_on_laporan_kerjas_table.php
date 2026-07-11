<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_kerjas', function (Blueprint $table) {
            $table->renameColumn('foto', 'foto_lama');
        });

        Schema::table('laporan_kerjas', function (Blueprint $table) {
            $table->json('foto')->nullable()->after('foto_lama');
        });

        DB::table('laporan_kerjas')->whereNotNull('foto_lama')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('laporan_kerjas')->where('id', $row->id)->update([
                    'foto' => json_encode([$row->foto_lama]),
                ]);
            }
        });

        Schema::table('laporan_kerjas', function (Blueprint $table) {
            $table->dropColumn('foto_lama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerjas', function (Blueprint $table) {
            $table->string('foto_lama')->nullable()->after('foto');
        });

        DB::table('laporan_kerjas')->whereNotNull('foto')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $photos = json_decode($row->foto, true) ?? [];

                DB::table('laporan_kerjas')->where('id', $row->id)->update([
                    'foto_lama' => $photos[0] ?? null,
                ]);
            }
        });

        Schema::table('laporan_kerjas', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('laporan_kerjas', function (Blueprint $table) {
            $table->renameColumn('foto_lama', 'foto');
        });
    }
};
