<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            // Todo plan nuevo empieza como 'pendiente'
            $table->enum('estado', ['pendiente', 'confirmado', 'cancelado'])->default('pendiente')->after('fecha_limite');
        });
    }

    public function down(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
