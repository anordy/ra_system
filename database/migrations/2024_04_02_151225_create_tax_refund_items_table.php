<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRefundItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_refund_items', function (Blueprint $table) {
            $table->id();
            $table->string('tansad_number')->nullable();
            $table->string('efd_number')->nullable();
            $table->decimal('exclusive_tax_amount', 20, 2)->nullable();
            $table->decimal('rate', 20, 2);
            $table->unsignedBigInteger('refund_id');
            $table->string('item_name')->nullable();
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
        Schema::dropIfExists('tax_refund_items');
    }
}
