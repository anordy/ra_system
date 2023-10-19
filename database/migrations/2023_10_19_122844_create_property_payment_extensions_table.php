<?php

use App\Enum\PaymentExtensionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPaymentExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_payment_extensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_payment_id')->index();
            $table->unsignedBigInteger('requested_by_id');
            $table->string('requested_by_type');
            $table->text('reasons');
            $table->dateTime('extension_from')->nullable();
            $table->dateTime('extension_to')->nullable();
            $table->text('marking')->nullable();
            $table->enum('status', PaymentExtensionStatus::getConstants());
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
        Schema::dropIfExists('property_payment_extensions');
    }
}
