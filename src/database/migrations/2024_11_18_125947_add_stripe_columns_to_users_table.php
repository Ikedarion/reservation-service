<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable(); //stripeのID
            $table->string('pm_type')->nullable(); //支払い方法
            $table->string('pm_last_four')->nullable(); // 支払い方法の下4桁
            $table->timestamp('trial_ends_at')->nullable(); // トライアル終了日（必要なら）
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumns(['stripe_id','pm_type','pm_last_four_trial_ends_at']);
        });
    }
}
