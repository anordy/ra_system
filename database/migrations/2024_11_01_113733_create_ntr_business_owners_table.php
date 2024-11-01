<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrBusinessOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_business_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('ntr_business_id');
            $table->unsignedBigInteger('nationality_id');
            $table->string('full_name');
            $table->string('address');
            $table->string('position')->nullable();
            $table->string('universal_tin')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport_attachment')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_name')->nullable();
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
        Schema::dropIfExists('ntr_business_owners');
    }
}
