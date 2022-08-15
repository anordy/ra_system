<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuantityCertificateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantity_certificate_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_id');
            $table->unsignedBigInteger('config_id');
            $table->string('cargo_name')->nullable();
            $table->decimal('liters_observed');
            $table->decimal('liters_at_20');
            $table->decimal('metric_tons')->nullable();
            $table->string('voyage_no')->nullable();
            $table->integer('download_count')->default(0);
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
        Schema::dropIfExists('quantity_certificate_items');
    }
}
