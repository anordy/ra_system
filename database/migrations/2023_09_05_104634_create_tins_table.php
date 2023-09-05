<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tins', function (Blueprint $table) {
            $table->id();
            $table->string('tin')->index('tin_number_index');
            $table->timestamp('date_of_birth');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender');
            $table->string('taxpayer_name');
            $table->string('trading_name');
            $table->string('postal_address')->nullable();
            $table->string('street');
            $table->string('plot_number')->nullable();
            $table->string('district');
            $table->string('region');
            $table->string('postal_code')->nullable();
            $table->string('mobile');
            $table->string('email');
            $table->string('vat_registration_number');
            $table->longText('biometric', 8000)->nullable();
            $table->integer('tra_sync_status')->default(0)->comment('0-Not synced, 1-Synced');
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
        Schema::dropIfExists('tins');
    }
}
