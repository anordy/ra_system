<?php

use App\Enum\Currencies;
use App\Enum\PBZPaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPbzToZmBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zm_bills', function (Blueprint $table) {
            $table->decimal('pbz_amount', 20, 2)->nullable();
            $table->enum('pbz_status', PBZPaymentStatusEnum::getConstants())->nullable();
            $table->enum('pbz_currency', Currencies::getConstants())->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zm_bills', function (Blueprint $table) {
            //
        });
    }
}
