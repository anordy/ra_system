<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_file_types', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('file_type')->nullable();
            $table->string('business_type')->nullable();
            $table->boolean('is_required')->default(false);
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
        Schema::dropIfExists('business_file_types');
    }
}
