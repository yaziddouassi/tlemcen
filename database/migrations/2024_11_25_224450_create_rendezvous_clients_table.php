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
        Schema::create('rendezvous_clients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')          // Crée unsignedBigInteger + index
                   ->constrained('users')                 // Crée la clé étrangère vers users.id
                   ->onDelete('cascade')           // ou restrict, set null, etc.
                   ->onUpdate('cascade');    

            $table->string('usernom');
            $table->string('userprenom');
            $table->string('usertelephone');
            $table->string('usermail');
            $table->string('useradresse');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jouractifs');
    }
};
