<?php

use App\Models\WaiverStatus;
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
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('debt_id');
            $table->string('debt_type');
            $table->enum('category', ['penalty', 'interest', 'both']);
            $table->string('ground')->nullable();
            $table->string('reason')->nullable();
            $table->decimal('penalty_rate', 20,2)->nullable();
            $table->decimal('interest_rate', 20,2)->nullable();
            $table->decimal('penalty_amount', 20,2)->nullable();
            $table->decimal('interest_amount', 20,2)->nullable();
            $table->string('waiver_report')->nullable();
            $table->string('notice_report')->nullable();
            $table->string('setting_report')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', WaiverStatus::getConstants())->default('pending');
            $table->string('marking')->nullable();
            $table->timestamp('approved_on')->nullable();
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
