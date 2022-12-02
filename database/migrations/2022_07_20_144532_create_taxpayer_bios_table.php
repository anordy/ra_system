<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayerBiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayer_bios', function (Blueprint $table) {
            $table->id();
            $table->string('taxpayer_reference_no');
            $table->enum('hand',['left', 'right']);
            $table->string('little_template')->nullable();
            $table->string('little_image')->nullable();
            $table->string('ring_template')->nullable();
            $table->string('ring_image')->nullable();
            $table->string('middle_template')->nullable();
            $table->string('middle_image')->nullable();
            $table->string('index_template')->nullable();
            $table->string('index_image')->nullable();            
            $table->string('thumb_template')->nullable();
            $table->string('thumb_image')->nullable();
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
        Schema::dropIfExists('taxpayer_bios');
    }
}
