<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnContactsToBusinessLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->string('contact_name')->nullable()->after('street');
            $table->string('mobile')->nullable()->after('street');
            $table->string('alt_mobile')->nullable()->after('street');
            $table->string('fax')->nullable()->after('street');
            $table->string('email')->nullable()->after('street');
            $table->string('po_box')->nullable()->after('street');
            $table->string('address_type')->nullable()->after('street');
        });
    }

}
