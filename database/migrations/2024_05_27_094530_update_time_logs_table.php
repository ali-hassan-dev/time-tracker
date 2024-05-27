<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->enum('talk_type', ['Chat', 'Talk'])->nullable()->change();
            $table->renameColumn('order_type', 'client');
            $table->unsignedInteger('talk_time')->nullable()->change();
            $table->renameColumn('made_percentage', 'payout');
            $table->enum('payment_method', ['Phone Pay', 'Crypto'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->string('talk_type')->change();
            $table->renameColumn('client', 'order_type');
            $table->time('talk_time')->change();
            $table->renameColumn('payout', 'made_percentage');
            $table->string('payment_method')->change();
        });
    }
};
