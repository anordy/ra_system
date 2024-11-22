<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRgCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rg_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rg_register_id');
            $table->unsignedBigInteger('commenter_id');
            $table->integer('commenter_type');
            $table->string('comment',255);
            $table->boolean('is_read');
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
        Schema::dropIfExists('rg_comments');
    }
}
