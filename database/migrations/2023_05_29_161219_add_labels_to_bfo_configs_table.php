<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelsToBfoConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bfo_configs', function (Blueprint $table) {
            $table->string('value_label')->nullable()->after('code');
            $table->string('rate_label')->nullable()->after('code');
            $table->string('tax_label')->nullable()->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bfo_configs', function (Blueprint $table) {
            //
        });
    }
}
