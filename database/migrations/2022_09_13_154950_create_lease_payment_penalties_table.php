<?php

use App\Enum\Currencies;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasePaymentPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_payment_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_payment_id');
            $table->enum('currency', Currencies::getConstants())->default('USD');
            $table->decimal('tax_amount', 20, 2);
            $table->decimal('rate_percentage', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('lease_payment_penalties');
    }
}
