<?php

use App\Enum\BillStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installment_id');
            $table->decimal('amount', 20, 2);
            $table->enum('currency', ['TZS', 'USD', 'EUR']);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->enum('status', BillStatus::getConstants())->default(BillStatus::SUBMITTED);
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
        Schema::dropIfExists('installment_items');
    }
}
