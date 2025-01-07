<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_number')->unique();
            $table->enum('payment_method', [
                'cash', 
                'transfer_bank',
                'e_wallet'
            ]);
            $table->enum('status', [
                'pending', 
                'paid', 
                'failed'
            ])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('proof_of_payment')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Menambahkan trigger untuk auto-generate payment number
        DB::unprepared('
            CREATE TRIGGER tr_generate_payment_number
            BEFORE INSERT ON payments
            FOR EACH ROW
            BEGIN
                SET NEW.payment_number = CONCAT("PAY", DATE_FORMAT(NOW(), "%Y%m%d"), LPAD(LAST_INSERT_ID(), 6, "0"));
            END
        ');
    }

    public function down()
    {
        // Hapus trigger sebelum menghapus tabel
        DB::unprepared('DROP TRIGGER IF EXISTS tr_generate_payment_number');
        
        Schema::dropIfExists('payments');
    }
}; 