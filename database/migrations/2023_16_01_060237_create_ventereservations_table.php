<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventereservations', function (Blueprint $table) {
            $table->id();
            $table->integer('identifiant');
            $table->string('nom');
            $table->integer('prixAchat');
            $table->integer('prixVente');
            $table->integer('quantite');
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventereservations');
    }
};
