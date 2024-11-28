<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dealer_id');
            $table->string('stripe_cus_id');
            $table->string('stripe_prod_id')->nullable();
            $table->string('stripe_subs_id')->nullable();
            $table->string('last_stripe_prod')->nullable();
            $table->string('last_stripe_subs')->nullable();
            $table->string('status')->nullable();
            $table->string('next_billing')->nullable();
            $table->string('receipt')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
