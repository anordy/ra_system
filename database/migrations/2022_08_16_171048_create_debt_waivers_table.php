<?php

use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtWaiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_waivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id')->comments('main Owner');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('debt_id')->nullable();
            $table->enum('category', ['penalty', 'interest', 'both']);
            $table->enum('business_type', ['hotel', 'other'])->default('other');
            $table->text('ground')->nullable();
            $table->text('reason')->nullable();
            $table->string('waiver_report')->nullable();
            $table->string('notice_report')->nullable();
            $table->string('setting_report')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ReturnStatus::getConstants());
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
        Schema::dropIfExists('debt_waivers');
    }
}
