<?php

use App\Enum\BankReconStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankReconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_recons', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->date('actual_transaction_date');
            $table->date('value_date');

            $table->string('transaction_type');
            $table->string('control_no');
            $table->string('payment_ref');
            $table->string('payer_name')->nullable();
            $table->string('transaction_origin')->nullable(); // Bank Branch

            // Explanation text, for future reference
            $table->string('original_record')->nullable();

            $table->decimal('debit_amount', 38, 2)->nullable();
            $table->decimal('credit_amount', 38, 2)->nullable();
            $table->string('currency', 5);
            $table->decimal('current_balance', 38, 2);
            $table->decimal('dr_cr')->nullable();
            $table->decimal('doc_num')->nullable();

            $table->boolean('is_reconciled')->default(false);
            $table->string('recon_status')->default(BankReconStatus::PENDING);

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
        Schema::dropIfExists('bank_recons');
    }
}
