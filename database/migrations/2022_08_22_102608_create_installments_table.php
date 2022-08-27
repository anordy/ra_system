<?php

use App\Enum\InstallmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('installment_request_id');
            $table->dateTime('installment_from')->nullable();
            $table->dateTime('installment_to')->nullable();
            $table->integer('installment_count')->nullable();
            $table->decimal('amount', 20, 2);
            $table->enum('currency', ['TZS', 'USD', 'EUR']);
            $table->enum('status', InstallmentStatus::getConstants())->default(InstallmentStatus::ACTIVE);
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
        Schema::dropIfExists('installments');
    }
}
