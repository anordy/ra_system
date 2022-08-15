<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxVerificationOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_verification_officers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('verification_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('team_leader')->default(false);
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
        Schema::dropIfExists('tax_verification_officers');
    }
}
