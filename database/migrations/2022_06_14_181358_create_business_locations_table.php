<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_region_id')->nullable();
            $table->string('zin')->nullable()->unique();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('ward_id');
            $table->unsignedBigInteger('street_id');
            $table->timestamp('date_of_commencing');
            $table->timestamp('effective_date')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('nature_of_possession',['Owned','Rented']);
            $table->string('physical_address')->nullable();
            $table->string('house_no')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_phone_no')->nullable();
            $table->decimal('pre_estimated_turnover', 20, 2)->nullable();
            $table->decimal('post_estimated_turnover', 20, 2);
            $table->string('meter_no')->nullable();
            $table->string('marking')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_headquarter')->default(false);
            $table->enum('status', ['draft', 'pending', 'approved', 'correction', 'rejected', 'temp_closed', 'deregistered'])->default('pending');
            $table->softDeletes();
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
        Schema::dropIfExists('business_locations');
    }
}
