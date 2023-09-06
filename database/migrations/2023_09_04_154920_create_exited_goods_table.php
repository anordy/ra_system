<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitedGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exited_goods', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_tin_number')->index('owner_tin_number_index');
            $table->integer('supplier_tin_number');
            $table->string('tansad_number');
            $table->timestamp('tansad_date');
            $table->string('vat_registration_number');
            $table->decimal('value_excluding_tax', 20, 2);
            $table->decimal('tax_amount', 20,2);
            $table->string('invoice_number');
            $table->timestamp('invoice_date');
            $table->timestamp('release_date');
            $table->string('custom_declaration_types')->comment('IM4 or IM9');
            $table->integer('status')->default(0)->comment('0-Not utilized, 1-Utilized');
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
        Schema::dropIfExists('exited_goods');
    }
}
