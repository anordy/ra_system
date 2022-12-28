<?php

use App\Enum\BillStatus;
use App\Enum\DisputeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id')->comments('main Owner');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('disputes_type_id');
            $table->enum('category', ['waiver', 'objection', 'waiver-and-objection'])->default('waiver');
            $table->enum('waiver_category', ['penalty', 'interest', 'both'])->nullable();
            $table->float('penalty_rate')->nullable();
            $table->float('interest_rate')->nullable();
            $table->float('penalty_amount')->nullable();
            $table->float('interest_amount')->nullable();
            $table->unsignedBigInteger('assesment_id')->nullable();
            $table->enum('business_type', ['hotel', 'other'])->default('other');
            $table->decimal('tax_in_dispute',38, 2)->default(0);
            $table->decimal('tax_not_in_dispute',38, 2)->default(0);
            $table->decimal('tax_deposit',38, 2)->default(0);
            $table->string('ground', 4000)->nullable();
            $table->string('reason', 4000)->nullable();
            $table->string('dispute_report')->nullable();
            $table->string('notice_report')->nullable();
            $table->string('setting_report')->nullable();
            $table->enum('app_status', DisputeStatus::getConstants())->default(DisputeStatus::DRAFT);
            $table->enum('payment_status', BillStatus::getConstants())->nullable();
            $table->string('marking')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('disputes');
    }
}
