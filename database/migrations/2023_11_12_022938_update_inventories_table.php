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
        // add price column to inventories table
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop price column from inventories table
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
