<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('stripe_cus_id');
            $table->dropColumn('stripe_prod_id');
            $table->dropColumn('stripe_subs_id');
            $table->dropColumn('last_stripe_prod');
            $table->dropColumn('last_stripe_subs');

            $table->unsignedInteger('dealer_id')->nullable()->change();
            $table->string('package')->nullable();
            $table->integer('upload_limit')->default(0);
            $table->string('payment_intent')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('subscription_end')->nullable();
            $table->text('payment_link')->nullable();
            $table->string('payment_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
