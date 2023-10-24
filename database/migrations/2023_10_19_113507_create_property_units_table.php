<?php

use App\Enum\UnitUsageTypeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->index('property_id_unit_index');
            $table->unsignedBigInteger('storey_id')->index('storey_id_property_index');
            $table->string('name');
            $table->string('house_number');
            $table->string('meter_number')->nullable();
            $table->enum('usage_type', UnitUsageTypeStatus::getConstants());

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
        Schema::dropIfExists('property_units');
    }
}
