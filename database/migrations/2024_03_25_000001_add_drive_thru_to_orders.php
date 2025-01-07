<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_drive_thru')->default(false)->after('user_id');
        });

        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->change();
            $table->string('recipient_phone')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_drive_thru');
        });

        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->string('recipient_name')->nullable(false)->change();
            $table->string('recipient_phone')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
        });
    }
}; 