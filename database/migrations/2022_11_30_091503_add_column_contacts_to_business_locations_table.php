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
            $table->string('contact_name')->nullable()->after('street_id');
            $table->string('mobile')->nullable()->after('street_id');
            $table->string('alt_mobile')->nullable()->after('street_id');
            $table->string('fax')->nullable()->after('street_id');
            $table->string('email')->nullable()->after('street_id');
            $table->string('po_box')->nullable()->after('street_id');
            $table->string('address_type')->nullable()->after('street_id');
        });
    }

}
