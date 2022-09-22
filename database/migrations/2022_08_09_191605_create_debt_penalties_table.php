<?php

use App\Enum\Currencies;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('debt_type');
            $table->string('financial_month_name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('tax_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->decimal('rate_amount', 20, 2);
            $table->decimal('rate_percentage', 20, 2);
            $table->decimal('late_payment', 20, 2);
            $table->decimal('late_filing', 20, 2);
            $table->decimal('currency_rate_in_tz', 20, 2)->default(1);
            $table->enum('currency',Currencies::getConstants())->default('TZS');
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
        Schema::dropIfExists('debt_penalties');
    }
}
