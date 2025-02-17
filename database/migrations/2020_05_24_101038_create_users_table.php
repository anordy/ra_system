<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status')->default(false);
            $table->boolean('is_first_login')->default(true);
            $table->unsignedBigInteger('role_id');
            $table->integer('auth_attempt')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
            $table->boolean('pass_expired_on')->default(0);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('level_id')->nullable();

            $table->string('ci_payload', 4000)->nullable();
            $table->boolean('failed_verification')->default(0);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
