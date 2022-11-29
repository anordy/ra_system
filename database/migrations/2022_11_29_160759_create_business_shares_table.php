<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_shares', function (Blueprint $table) {
            $table->id();
            $table->string('business_id');
            $table->string('share_holder_id');
            $table->string('shareholder_name')->nullable();
            $table->string('share_class')->nullable();
            $table->string('number_of_shares')->nullable();
            $table->string('currency')->nullable();
            $table->string('number_of_shares_taken')->nullable();
            $table->string('number_of_shares_paid')->nullable();
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
        Schema::dropIfExists('business_shares');
    }
}
