<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAuthoritiesVerifiedAtToBusinesses extends Migration
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
            $table->enum('bpra_verification_status', ['pending', 'approved', 'unverified'])->default('pending')->after('created_at')->nullable();
            $table->timestamp('authorities_verified_at')->after('created_at')->nullable();
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
            $table->dropColumn('bpra_verification_status');
            $table->dropColumn('authorities_verified_at');
        });
    }
}
