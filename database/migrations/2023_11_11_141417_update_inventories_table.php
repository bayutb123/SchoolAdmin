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
        // add issue_status_id column to issues table
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('issue_status')->nullable()->after('issue_id');

            $table->foreign('issue_status')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop issue_status_id column from issues table
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('issue_status');
        });
    }
};
