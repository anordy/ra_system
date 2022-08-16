<?php

use App\Enum\BillStatus;
use App\Enum\DisputeStatus;
use App\Enum\PaymentStatus;
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
            $table->unsignedBigInteger('assesment_id')->nullable();
            $table->enum('business_type', ['hotel', 'other'])->default('other');
            $table->decimal('tax_in_dispute', 40, 2)->default(0);
            $table->decimal('tax_not_in_dispute', 40, 2)->default(0);
            $table->decimal('tax_deposit', 40, 2)->default(0);
            $table->text('ground')->nullable();
            $table->text('reason')->nullable();
            $table->string('dispute_report')->nullable();
            $table->string('notice_report')->nullable();
            $table->string('setting_report')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('app_status', DisputeStatus::getConstants())->default(DisputeStatus::DRAFT);
            $table->enum('status', BillStatus::getConstants())->nullable();
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
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
