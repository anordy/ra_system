<?php

use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Enum\UnitUsageTypeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('house_number')->nullable();
            $table->string('urn')->nullable();
            $table->string('interview_id')->nullable();
            $table->unsignedBigInteger('ownership_type_id')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('size')->nullable();
            $table->string('features')->nullable();
            $table->string('property_value')->nullable();
            $table->string('purchase_value')->nullable();
            $table->timestamp('acquisition_date')->nullable();

            $table->string('region_id');
            $table->string('district_id');
            $table->string('ward_id');
            $table->string('street_id')->nullable();


            $table->string('status')->default('pending');
            $table->string('type');
            $table->string('usage_type');

            $table->unsignedBigInteger('hotel_stars_id')->nullable();
            $table->unsignedBigInteger('taxpayer_id');

            $table->text('marking')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
