<?php

use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');

            $table->unsignedBigInteger('return_id');
            $table->string('return_type');

            $table->unsignedBigInteger('filed_by_id');
            $table->string('filed_by_type');

            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_month_id');

            $table->enum('currency', ['TZS', 'USD']);

            $table->float('principal', 20, 2);
            $table->float('interest', 20, 2);
            $table->float('penalty', 20, 2);

            $table->float('infrastructure', 20, 2)->nullable();

            // Seaport & airport
            $table->float('airport_safety_fee', 20, 2)->nullable();
            $table->float('airport_service_charge', 20, 2)->nullable();
            $table->float('seaport_service_charge', 20, 2)->nullable();
            $table->float('seaport_transport_charge', 20, 2)->nullable();
            $table->float('infrastructure_znz_znz', 20, 2)->nullable();
            $table->float('infrastructure_znz_tz', 20, 2)->nullable();

            // Petroleum
            $table->float('rdf_fee', 20, 2)->nullable();
            $table->float('road_license_fee', 20, 2)->nullable();

            $table->float('withheld_tax', 20, 2)->nullable();
            $table->float('credit_brought_forward', 20, 2)->nullable();

            $table->float('total_amount', 20, 2)->nullable();
            $table->float('outstanding_amount', 20, 2)->nullable();

            $table->unsignedBigInteger('lumpsum_quarter')->nullable();

            $table->boolean('has_claim')->default(false);
            $table->boolean('is_nill')->default(false);

            $table->enum('filing_method', ['normal', 'method-one', 'method-two'])->default('normal');
            $table->enum('return_status', ['adjusted', 'self-assessment', 'submitted', 'draft'])->default('submitted');
            $table->enum('payment_status', ReturnStatus::getConstants())->default('submitted');
            $table->enum('return_category', ['normal', 'debt', 'overdue'])->default('normal');

            $table->enum('application_step', ['filing', 'debt', 'overdue'])->default('filing');
            $table->enum('application_status', ['normal', 'waiver', 'installment', 'extension'])->default('normal');
            $table->enum('payment_method', ['installment', 'full'])->nullable();

            $table->dateTime('paid_at')->nullable();
            $table->dateTime('payment_due_date');
            $table->dateTime('filing_due_date');

            $table->dateTime('curr_payment_due_date');
            $table->dateTime('curr_filing_due_date');

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
        Schema::dropIfExists('returns');
    }
}
