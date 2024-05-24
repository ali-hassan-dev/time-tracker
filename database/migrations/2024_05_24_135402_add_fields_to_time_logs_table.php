<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->timestamp('login_time')->nullable()->after('user_id');
            $table->timestamp('logout_time')->nullable()->after('login_time');
            $table->string('talk_type')->nullable()->after('logout_time');
            $table->time('talk_time')->nullable()->after('talk_type');
            $table->string('order_type')->nullable()->after('talk_time');
            $table->date('date')->nullable()->after('order_type');
            $table->float('made_percentage')->nullable()->after('date');
            $table->string('country')->nullable()->after('made_percentage');
            $table->string('payment_method')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->dropColumn(['login_time', 'logout_time', 'talk_type', 'talk_time', 'order_type', 'date', 'made_percentage', 'country', 'payment_method']);
        });
    }
};
