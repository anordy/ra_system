<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmBillChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_bill_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zm_bill_id');
            $table->string('control_number')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->string('ack_status')->nullable();
            $table->dateTime('ack_date')->nullable();
            $table->string('clb_status')->nullable();
            $table->dateTime('clb_date')->nullable();
            $table->enum('category', ['cancel', 'update', 'regenerate']);
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
        Schema::dropIfExists('zm_bill_changes');
    }
}
