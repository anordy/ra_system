<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxReturnHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_return_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_return_id');
            $table->longText('return_info')->nullable();
            $table->longText('return_items')->nullable();
            $table->longText('penalties')->nullable();
            $table->decimal('version')->default(1);
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
        Schema::dropIfExists('tax_return_histories');
    }
}
