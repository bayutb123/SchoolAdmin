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
        // add new column named last_author_id
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('status')->after('size_unit')->default('Belum Diketahui');
            $table->unsignedBigInteger('last_author_id')->after('status');
            
            $table->foreign('last_author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop the foreign key constraint
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['last_author_id']);
        });

        // drop the column
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('last_author_id');
        });
    }
};
