<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalBusinessUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_business_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->enum('type', ['effective_date', 'isic', 'tax_type', 'hotel_stars', 'lto', 'electric', 'tax_region', 'currency']);
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->string('marking')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->unsignedBigInteger('triggered_by');
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
        Schema::dropIfExists('internal_business_updates');
    }
}
