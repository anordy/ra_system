<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->string('financial_year_id');
            $table->string('taxtype_code');
            $table->string('business_type')->nullable();
            $table->decimal('total_output_tax', 40,2);
            $table->decimal('total_input_tax', 40,2);
            $table->decimal('total_vat_payable', 40,2);
            $table->decimal('vat_withheld', 40,2);
            $table->decimal('infrastructure_tax', 40,2)->nullable();
            $table->decimal('total_vat_amount_due', 40,2);
            $table->string('has_exemption');
            $table->enum('status',['submitted','complete']);
            $table->integer('editing_count',0);
            $table->string('method_used')->nullable();
            $table->unsignedBigInteger('filled_by_id');
            $table->string('filled_by_type');
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
        Schema::dropIfExists('vat_returns');
    }
}
