<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id')->comments('main Owner');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('filled_id');
            $table->unsignedBigInteger('assesment_id')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'correction', 'closed'])->default('draft');
            $table->enum('business_type', ['hotel', 'other'])->default('other');
            $table->decimal('tax_in_dispute', 40, 2)->default(0);
            $table->decimal('tax_not_in_dispute', 40, 2)->default(0);
            $table->decimal('objection_requirement', 40, 2)->default(0);
            $table->string('marking')->nullable();
            $table->text('ground_objection')->nullable();
            $table->text('reason_objection')->nullable();
            $table->string('attachments')->nullable();
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
        Schema::dropIfExists('objections');
    }
}
