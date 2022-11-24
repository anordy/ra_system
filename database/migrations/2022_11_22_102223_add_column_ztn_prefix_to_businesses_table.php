<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnZtnPrefixToBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            //
            $table->string('ztn_number')->after('isiic_iv')->nullable()->unique();
            $table->unsignedInteger('no_of_branches')->after('isiic_iv')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            //
            $table->dropColumn('no_of_branches');
            $table->dropColumn('ztn_number');
        });
    }
}
