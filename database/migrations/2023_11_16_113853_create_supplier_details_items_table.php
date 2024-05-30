<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierDetailsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_details_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('supplier_detail_id');
            $table->string('name');
            $table->decimal('price', 20,2);
            $table->decimal('total_amount', 20,2);
            $table->string('quantity');
            $table->boolean('is_taxable');
            $table->boolean('used')->default(false);
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
        Schema::dropIfExists('supplier_details_items');
    }
}
