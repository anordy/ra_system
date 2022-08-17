<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtWaiverHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_waiver_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_waiver_id');
            $table->unsignedBigInteger('taxpayer_id')->comments('main Owner');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('assesment_type_id');
            $table->unsignedBigInteger('assesment_id')->nullable();
            $table->enum('category', ['penalty', 'interest', 'both']);
            $table->enum('business_type', ['hotel', 'other'])->default('other');
            $table->text('ground')->nullable();
            $table->text('reason')->nullable();
            $table->string('waiver_report')->nullable();
            $table->string('notice_report')->nullable();
            $table->string('setting_report')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'correction', 'closed'])->default('draft');
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
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
        Schema::dropIfExists('debt_waiver_histories');
    }
}
