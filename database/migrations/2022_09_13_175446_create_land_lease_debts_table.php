<?php

use App\Enum\Currencies;
use App\Enum\LeaseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandLeaseDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_lease_debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_payment_id');
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->enum('currency', Currencies::getConstants())->default('USD');
            $table->decimal('original_total_amount', 20, 2);
            $table->decimal('penalty', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->decimal('outstanding_amount', 20, 2);
            $table->enum('status', LeaseStatus::getConstants());
            $table->dateTime('last_due_date');
            $table->dateTime('curr_due_date');
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
        Schema::dropIfExists('land_lease_debts');
    }
}
