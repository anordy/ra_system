<?php

use App\Enum\AssistantStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessAssistantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_assistants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->index();
            $table->unsignedBigInteger('taxpayer_id')->index();
            $table->string('added_by_type');
            $table->unsignedBigInteger('added_by_id');
            $table->enum('status', AssistantStatus::getConstants())->index();
            $table->string('agreement_attachment')->nullable();
            $table->dateTime('assigned_at');
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
        Schema::dropIfExists('business_assistants');
    }
}
