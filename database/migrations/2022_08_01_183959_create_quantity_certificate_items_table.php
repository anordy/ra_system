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
            $table->decimal('liters_observed', 20, 2);
            $table->decimal('liters_at_20', 20, 2);
            $table->decimal('metric_tons', 20, 2)->nullable();
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
