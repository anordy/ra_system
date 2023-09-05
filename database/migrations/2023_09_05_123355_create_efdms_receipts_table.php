<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEfdmsReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('efdms_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('seller_tin');
            $table->string('seller_vrn');
            $table->string('buyer_tin');
            $table->string('receipt_number');
            $table->timestamp('receipt_date');
            $table->string('verification_code');
            $table->decimal('total_tax_exclusive', 20, 2);
            $table->decimal('total_tax_inclusive', 20, 2);
            $table->decimal('total_tax_amount', 20, 2);
            $table->integer('isCancelled');
            $table->integer('isOnHold');
            $table->integer('isUtilized')->default(0)->comment('0-Not utilized, 1- Utilized');
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
        Schema::dropIfExists('efdms_receipts');
    }
}
