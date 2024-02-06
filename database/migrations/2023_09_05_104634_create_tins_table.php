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
            $table->timestamp('date_of_birth')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('taxpayer_name')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('street')->nullable();
            $table->string('plot_number')->nullable();
            $table->string('district')->nullable();
            $table->string('region')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('vat_registration_number')->nullable();
            $table->longText('biometric', 8000)->nullable();
            $table->integer('tra_sync_status')->default(0)->comment('0-Not synced, 1-Synced');
            $table->enum('tin_verification_status', ['pending', 'approved', 'unverified'])->default('pending')->nullable();
            $table->timestamp('registration_date');
            $table->string('nationality')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('block_number')->nullable();
            $table->boolean('is_business_tin')->nullable();
            $table->boolean('is_entity_tin')->nullable();
            $table->string('status');
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
