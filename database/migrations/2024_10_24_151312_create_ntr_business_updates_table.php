<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrBusinessUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_business_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ntr_business_id');
            $table->unsignedBigInteger('ntr_taxpayer_id');
            $table->text('current_business_info', 500);
            $table->text('current_contacts', 400)->nullable();
            $table->text('current_socials', 400)->nullable();
            $table->text('current_attachments', 300)->nullable();
            $table->text('new_business_info', 500)->nullable();
            $table->text('new_contacts', 400)->nullable();
            $table->text('new_socials', 400)->nullable();
            $table->text('new_attachments', 300)->nullable();
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
        Schema::dropIfExists('ntr_business_updates');
    }
}
