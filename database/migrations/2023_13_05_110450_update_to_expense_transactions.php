<?php

use Illuminate\Support\Facades\DB;
use App\Models\expenseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expense_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE expense_transactions CHANGE COLUMN payment_status payment_status ENUM('paid', 'partial', 'due') NOT NULL DEFAULT 'due'");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_transactions', function (Blueprint $table) {
            expenseTransactions::where('payment_status', 'pending')->update(['payment_status' => 'paid']);


            //
        });
    }
};
