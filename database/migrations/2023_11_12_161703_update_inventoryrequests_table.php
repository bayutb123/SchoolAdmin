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
        // add total price column
        Schema::table('inventoryrequests', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop total price column
        Schema::table('inventoryrequests', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }
};
