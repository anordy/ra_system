<?php

use App\Enum\LeaseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('land_lease_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->string('financial_month_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->enum('currency',['TZS', 'USD'])->default('USD');
            $table->decimal('total_amount', 40, 2)->default(0);
            $table->decimal('total_amount_with_penalties', 40, 2)->default(0);
            $table->decimal('outstanding_amount', 40, 2)->default(0);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->enum('status', LeaseStatus::getConstants());
            $table->dateTime('due_date')->nullable();
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('lease_payments');
    }
}
