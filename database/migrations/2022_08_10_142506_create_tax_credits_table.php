<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('claim_id');
            $table->enum('payment_method', ['cash', 'full', 'installment']);
            $table->decimal('amount', 20, 2);
            $table->enum('currency', ['TZS', 'USD', 'EUR'])->default('TZS');
            $table->unsignedInteger('installments_count')->nullable();
            $table->string('status')->nullable(); // Change to enum
            $table->enum('payment_status',['pending','paid', 'paid-partially'])->default('pending'); // Change to enum
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
        Schema::dropIfExists('tax_credits');
    }
}
