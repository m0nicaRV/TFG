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
        Schema::create('disponibilidads', function (Blueprint $table) {
            $table->id();
            $table->time('inicio');
            $table->time('fin');
            $table->string('semana');
            $table->bigInteger('cita_id')->unsigned();
            $table->foreign('cita_id')->references('id')->on('citas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidad');
    }
};
