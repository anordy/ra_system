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
            $table->text('little_template')->nullable();
            $table->text('little_image')->nullable();
            $table->text('ring_template')->nullable();
            $table->text('ring_image')->nullable();
            $table->text('middle_template')->nullable();
            $table->text('middle_image')->nullable();
            $table->text('index_template')->nullable();
            $table->text('index_image')->nullable();            
            $table->text('thumb_template')->nullable();
            $table->text('thumb_image')->nullable();
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
