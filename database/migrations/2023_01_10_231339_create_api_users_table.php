<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_users', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_url')->unique();
            $table->string('username');
            $table->string('password');
            $table->boolean('status')->default(true);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
            $table->string('ci_payload', 700)->nullable();
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
        Schema::dropIfExists('api_users');
    }
}
