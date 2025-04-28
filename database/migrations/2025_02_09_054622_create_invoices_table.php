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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('amount')->default(0);
            $table->integer('credit_amount')->default(1); // 5x bayar
            $table->integer('type')->default(1)->comment('1 = credit | 2 = one-time');
            $table->integer('status')->default(1); // 0 = disable | -1 = deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
