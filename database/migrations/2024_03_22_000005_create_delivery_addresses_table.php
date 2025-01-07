<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('address');
            $table->string('postal_code')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_addresses');
    }
}; 