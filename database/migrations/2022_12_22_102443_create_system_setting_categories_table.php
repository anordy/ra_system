<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_setting_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 4000);
            $table->string('code', 4000);
            $table->string('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
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
        Schema::dropIfExists('system_setting_categories');
    }
}
