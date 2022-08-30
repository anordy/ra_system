<?php

use App\Enum\Currencies;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLumpSumPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lump_sum_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->string('return_quater');
            $table->enum('currency', Currencies::getConstants())->default('TZS');
            $table->decimal('currency_rate_in_tz', 20, 2)->default(1);
            $table->decimal('tax_amount', 20, 2);
            $table->integer('late_filing')->default(0);
            $table->decimal('late_payment', 20, 2);
            $table->decimal('rate_percentage', 20, 2);
            $table->decimal('rate_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('lump_sum_return_penalties');
    }
}
