<?php

use App\Enum\InstallmentRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_return_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->dateTime('installment_from')->nullable();
            $table->dateTime('installment_to')->nullable();
            $table->integer('installment_count')->nullable();
            $table->text('reasons');
            $table->text('ground');
            $table->string('attachment')->nullable();
            $table->string('marking')->nullable();
            $table->enum('status', InstallmentRequestStatus::getConstants());
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
        Schema::dropIfExists('installment_requests');
    }
}
