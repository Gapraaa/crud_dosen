<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->char('nidn', 10)->primary();
            $table->string('nama_dosen', 50);
            $table->date('tgl_mulai_tugas');
            $table->string('jenjang_pendidikan', 10);
            $table->string('bidang_keilmuan', 50);
            $table->string('foto_dosen', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};

