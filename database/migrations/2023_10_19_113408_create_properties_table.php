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
            $table->string('name');
            $table->string('house_number');
            $table->string('urn')->nullable();

            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('ward_id');
            $table->unsignedBigInteger('street_id');

            $table->enum('status', PropertyStatus::getConstants())->default('pending');
            $table->enum('type', PropertyTypeStatus::getConstants());
            $table->enum('usage_type', UnitUsageTypeStatus::getConstants());

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
