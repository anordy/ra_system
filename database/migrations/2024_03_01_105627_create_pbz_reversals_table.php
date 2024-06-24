<?php

use App\Enum\Currencies;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbzReversalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbz_reversals', function (Blueprint $table) {
            $table->id();
            $table->dateTime('transaction_time');
            $table->string('bank_ref');
            $table->string('control_number')->nullable();
            $table->decimal('amount', 20, 2);
            $table->enum('currency', Currencies::getConstants());
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
        Schema::dropIfExists('pbz_reversals');
    }
}
