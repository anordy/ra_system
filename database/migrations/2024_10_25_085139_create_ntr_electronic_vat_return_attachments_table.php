<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrElectronicVatReturnAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_electronic_vat_return_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('business_id');
            $table->string('customer_name');
            $table->string('service_description');
            $table->enum('currency', ['USD', 'TZS'])->default('USD');
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->timestamp('transaction_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ntr_electronic_vat_return_attachments');
    }
}
