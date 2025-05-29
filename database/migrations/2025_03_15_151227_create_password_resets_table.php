<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('visiteur', function (Blueprint $table) {
            $table->string('mdp', 255)->nullable(false)->change(); // Modifier la colonne 'mdp' à 255 caractères
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('visiteur', function (Blueprint $table) {
            $table->string('mdp', 45)->nullable(false)->change(); // Revenir à 45 caractères si nécessaire
        });
    }
};
