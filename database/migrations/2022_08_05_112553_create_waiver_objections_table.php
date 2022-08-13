<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaiverObjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waiver_objections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id')->comments('main Owner');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('filled_id');
            $table->enum('status', ['draft', 'pending', 'approved', 'correction', 'closed'])->default('draft');
            $table->unsignedBigInteger('assesment_id')->nullable();
            $table->enum('business_type', ['hotel', 'other'])->default('other');
            $table->decimal('tax_in_dispute', 40, 2)->default(0);
            $table->decimal('tax_not_in_dispute', 40, 2)->default(0);
            $table->string('marking')->nullable();
            $table->text('ground')->nullable();
            $table->text('reason')->nullable();
            $table->string('objection_report')->nullable();
            $table->string('notice_report')->nullable();
            $table->string('setting_report')->nullable();
            $table->enum('type', ['waiver', 'objection', 'both']);
            $table->timestamp('verified_at')->nullable();
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
        Schema::dropIfExists('waiver_objections');
    }
}
