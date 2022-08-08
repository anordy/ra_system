<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetroleumReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petroleum_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('config_id');
            $table->decimal('value');
            $table->decimal('vat');
            $table->enum('status',['submitted', 'complete'])->default('submitted');
            $table->string('currency');
            $table->integer('edited_count')->default(0);
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
        Schema::dropIfExists('petroleum_return_items');
    }
}
