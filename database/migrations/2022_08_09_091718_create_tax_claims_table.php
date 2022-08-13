<?php

use App\Enum\TaxClaimStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id'); // Can be removed
            $table->unsignedBigInteger('business_id'); // Can be removed
            $table->unsignedBigInteger('tax_type_id'); // Can be removed

            $table->unsignedBigInteger('old_return_id');
            $table->string('old_return_type');

            $table->unsignedBigInteger('new_return_id')->nullable();
            $table->string('new_return_type')->nullable();

            $table->decimal('amount', 20, 2);
            $table->enum('currency', ['TZS', 'USD', 'EUR'])->default('TZS');
            $table->unsignedBigInteger('financial_month_id');

            $table->string('marking')->nullable();
            $table->enum('status', TaxClaimStatus::getConstants())->default(TaxClaimStatus::DRAFT);

            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type');
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
        Schema::dropIfExists('tax_claims');
    }
}
