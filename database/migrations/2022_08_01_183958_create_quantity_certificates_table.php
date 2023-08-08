<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuantityCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantity_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->string('ship');
            $table->string('port');
            $table->string('voyage_no')->nullable();
            $table->date('ascertained')->nullable();
            $table->integer('download_count')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->enum('status', ['draft', 'pending', 'filled', 'correction', 'accepted'])->default('pending');
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
        Schema::dropIfExists('quantity_certificates');
    }
}
