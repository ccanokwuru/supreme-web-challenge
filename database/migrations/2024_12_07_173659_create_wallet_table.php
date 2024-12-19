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
        Schema::create('wallets', function (Blueprint $table) {
            //
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('wallet_type_id')
                ->nullable()
                ->on('wallet_types')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->string('currency')->default('NGN');
            $table->decimal('balance')->default('0.00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
