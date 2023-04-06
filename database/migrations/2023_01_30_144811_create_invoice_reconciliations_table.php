<?php

use App\Enum\InvoiceReconStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->date('date');
            $table->decimal('amount', 38, 2);
            $table->enum('currency', ['TZS', 'USD', 'EUR']);
            $table->string('status')->default(InvoiceReconStatus::PENDING);
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
        Schema::dropIfExists('invoice_reconciliations');
    }
}
