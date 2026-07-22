<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 20)->unique();
            $table->date('date');
            $table->foreignId('singapore_partner_id')->constrained('singapore_partners')->onDelete('cascade');
            $table->foreignId('leader_id')->constrained('leaders')->onDelete('cascade');
            $table->decimal('company_balance_amount', 15, 2)->default(0);
            $table->decimal('sg_commission_percentage', 5, 2)->default(5.00);
            $table->decimal('leader_commission_percentage', 5, 2)->default(10.00);
            $table->decimal('total_commission', 15, 2)->default(0);
            $table->decimal('sg_commission_amount', 15, 2)->default(0);
            $table->decimal('leader_commission_amount', 15, 2)->default(0);
            $table->decimal('recipient_total_commission', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('transaction_code');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

