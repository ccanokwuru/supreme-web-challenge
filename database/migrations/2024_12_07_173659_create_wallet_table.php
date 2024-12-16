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
        Schema::table('wallets', function (Blueprint $table) {
            //
            $table->id();
            $table->foreign('user_id')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('wallet_type_id')->onDelete('set null')->onUpdate('cascade');
            $table->string('currency')->default('NGN');
            $table->decimal('balance')->default(0);
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
