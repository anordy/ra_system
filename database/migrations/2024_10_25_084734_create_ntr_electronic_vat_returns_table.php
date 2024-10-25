<?php

use App\Enum\ReturnCategory;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrElectronicVatReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_electronic_vat_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('filed_by_id');
            $table->enum('currency',['TZS', 'USD'])->default('USD');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->integer('edited_count')->default(0);
            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('return_category', ReturnCategory::getConstants())->default(ReturnCategory::NORMAL);
            $table->decimal('principal', 20, 2)->default(0);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->decimal('total_amount_due', 20, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties', 20, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->date('filing_due_date')->nullable();
            $table->date('payment_due_date')->nullable();
            $table->date('curr_payment_due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ntr_electronic_vat_returns');
    }
}
