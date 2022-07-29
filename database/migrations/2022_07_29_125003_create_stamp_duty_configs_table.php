<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampDutyConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_duty_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financia_year_id');
            $table->integer('order')->default(0);
            $table->string('code');
            $table->string('name');
            $table->enum('row_type', ['dynamic', 'unremovable', 'withheld'])->default('dynamic');
            $table->boolean('value_calculated')->default(false);
            $table->enum('col_type', ['total', 'subtotal', 'normal', 'heading', 'withheld'])->default('normal');
            $table->boolean('rate_applicable')->default(true);
            $table->enum('rate_type', ['percentage', 'fixed'])->nullable();
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('rate')->unsigned()->default(0);
            $table->decimal('rate_usd')->nullable();
            $table->string('formular')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('stamp_duty_configs');
    }
}
