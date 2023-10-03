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
            $table->string('good_id');
            $table->string('owner_tin_number')->index('owner_tin_number_index');
            $table->string('supplier_tin_number');
            $table->string('tansad_number');
            $table->timestamp('tansad_date');
            $table->string('vat_registration_number')->nullable();
            $table->decimal('value_excluding_tax', 20, 2)->default(0);
            $table->decimal('tax_amount', 20,2)->default(0);
            $table->string('invoice_number')->nullable();
            $table->string('invoice_date')->nullable();
            $table->timestamp('release_date')->nullable();
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
