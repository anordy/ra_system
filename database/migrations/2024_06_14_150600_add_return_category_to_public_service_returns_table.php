<?php

use App\Enum\ReturnCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnCategoryToPublicServiceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_service_returns', function (Blueprint $table) {
            $table->string('return_category')->default(ReturnCategory::NORMAL);
            $table->timestamp('payment_date');
            $table->timestamp('curr_payment_date');
            $table->timestamp('principal');
            $table->timestamp('interest');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_service_returns', function (Blueprint $table) {
            //
        });
    }
}
