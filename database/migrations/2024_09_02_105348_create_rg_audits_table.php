<?php

use App\Enum\ReportRegister\RgAuditEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRgAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rg_audits', function (Blueprint $table) {
            $table->id();
            $table->enum('event', RgAuditEvent::getConstants());
            $table->integer('actor_type');
            $table->unsignedBigInteger('actor_id');
            $table->unsignedBigInteger('rg_register_id');
            $table->string('description', 255)->nullable();
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
        Schema::dropIfExists('rg_audits');
    }
}
