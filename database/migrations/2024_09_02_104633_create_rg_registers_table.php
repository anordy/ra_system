<?php

use App\Enum\ReportRegister\RgPriority;
use App\Enum\ReportRegister\RgStatus;
use App\Enum\ReportRegister\RgTaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRgRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rg_registers', function (Blueprint $table) {
            $table->id();
            $table->integer('requester_type');
            $table->integer('is_scheduled');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('rg_category_id')->nullable();
            $table->unsignedBigInteger('rg_sub_category_id')->nullable();
            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->integer('register_type');
            $table->string('title', 255);
            $table->string('description', 255);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('resolved_date')->nullable();
            $table->timestamp('breach_date')->nullable();
            $table->enum('status', array_merge(RgStatus::getConstants(), RgTaskStatus::getConstants()));
            $table->enum('priority', RgPriority::getConstants())->nullable();
            $table->boolean('is_breached')->default(false);
            $table->string('code');
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
        Schema::dropIfExists('rg_registers');
    }
}
