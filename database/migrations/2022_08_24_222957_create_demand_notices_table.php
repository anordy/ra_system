<?php

use App\Enum\ReturnCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_notices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('debt_type');
            $table->enum('sent_by', ['job', 'user']);
            $table->integer('paid_within_days');
            $table->timestamp('next_notify_date')->nullable();
            $table->timestamp('sent_on');
            $table->enum('category', ReturnCategory::getConstants())->default('normal');
            $table->unsignedBigInteger('sent_by_id')->nullable();
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
        Schema::dropIfExists('debt_demand_notices');
    }
}
