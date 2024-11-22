<?php

use App\Enum\ReportRegister\RgAssignmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRgAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rg_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignee_id');
            $table->unsignedBigInteger('assigner_id');
            $table->unsignedBigInteger('rg_register_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamp('assigned_date');
            $table->enum('status', RgAssignmentStatus::getConstants());
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
        Schema::dropIfExists('rg_assignments');
    }
}
