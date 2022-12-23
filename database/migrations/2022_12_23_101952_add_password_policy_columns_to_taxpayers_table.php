<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordPolicyColumnsToTaxpayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxpayers', function (Blueprint $table) {
            //
            $table->timestamp('pass_expired_on')->after('updated_at')->nullable();
            $table->boolean('is_password_expired')->after('password')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxpayers', function (Blueprint $table) {
            //
            $table->dropColumn('pass_expired_on');
            $table->dropColumn('is_password_expired');
        });
    }
}
