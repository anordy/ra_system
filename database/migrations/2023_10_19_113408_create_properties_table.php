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

            $table->string('region_id');
            $table->string('district_id');
            $table->string('ward_id');
            $table->string('street_id')->nullable();

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
