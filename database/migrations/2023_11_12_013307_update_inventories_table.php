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
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id')->nullable()->after('id');
            $table->unsignedBigInteger('request_status')->nullable()->after('request_id');
            $table->string('description')->nullable()->change();

            $table->foreign('request_status')->references('id')->on('status');
            $table->foreign('request_id')->references('id')->on('inventoryrequests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('request_id');
            $table->dropColumn('request_status');
        });
    }
};
