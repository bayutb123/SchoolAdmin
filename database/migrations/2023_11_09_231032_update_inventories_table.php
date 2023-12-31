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
        // make description nullable
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('issue_id')->nullable()->after('id');
            $table->string('description')->nullable()->change();
            
            $table->foreign('issue_id')->references('id')->on('inventoryissues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // make description not nullable
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });
    }
};
