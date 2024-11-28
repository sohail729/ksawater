<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

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
            $table->enum('type', ['admin','dealer','client'])->default('dealer');
            $table->string('package');
            $table->integer('upload_limit')->default(0);
            $table->string('username')->unique();
            $table->string('fullname');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_intent')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('subscription_end')->nullable();
            $table->string('payment_session')->nullable();
            $table->text('payment_link')->nullable();
            $table->string('payment_email')->nullable();
            $table->string('is_verified')->default(0);
            $table->dateTime('verified_on')->nullable();
            $table->dateTime('last_verified')->nullable();
            $table->string('is_featured')->default(0);
            $table->string('featured_limit')->default(0);
            $table->dateTime('featured_start')->nullable();
            $table->dateTime('featured_end')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('salt')->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(0);
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
