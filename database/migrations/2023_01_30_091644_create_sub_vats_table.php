<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubVatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_vats', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('gfs_code')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
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
        Schema::dropIfExists('sub_vats');
    }
}
