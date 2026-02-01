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
        Schema::create('rendezvous_horaires', function (Blueprint $table) {
            $table->id();

            $table->string('debut');
            $table->string('fin');
            $table->string('jour');
            $table->string('mois');
            $table->string('annee');
            $table->string('journee');
            $table->date('ladate');
            $table->string('userid');
            $table->string('usernom')->nullable();
            $table->string('userprenom')->nullable();
            $table->string('usertelephone')->nullable();
            $table->string('usermail')->nullable(); 
            $table->string('useradresse')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaires');
    }
};

