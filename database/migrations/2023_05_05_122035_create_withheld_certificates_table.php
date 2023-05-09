<?php

use App\Enum\Currencies;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithheldCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withheld_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('tax_return_id')->nullable();

            $table->string('withholding_receipt_no');
            $table->date('withholding_receipt_date');
            $table->string('vfms_receipt_no');
            $table->date('vfms_receipt_date');
            $table->string('agent_name');
            $table->string('agent_no');
            $table->decimal('net_amount', 20, 2);
            $table->decimal('tax_withheld', 20, 2);
            $table->enum('currency', Currencies::getConstants());

            // For morphing to specific return.
            $table->unsignedBigInteger('return_id');
            $table->string('return_type');

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
        Schema::dropIfExists('withheld_certificates');
    }
}
