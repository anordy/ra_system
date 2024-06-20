<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLandLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land_leases', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('approved_at')->nullable();
            $table->string('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('land_leases', function (Blueprint $table) {
            $table->dropColumn('approved_by');
            $table->dropColumn('completed_by');
            $table->dropColumn('completed_at');
            $table->dropColumn('approved_at');
            $table->dropColumn('comments');
        });
    }
}
