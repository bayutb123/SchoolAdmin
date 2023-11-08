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
        Schema::create('inventoryissues', function (Blueprint $table) {
            $table->id();
            $table->integer('issue_group_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('room_id');
            $table->string('description');
            $table->string('status');
            $table->date('issued_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventoryissues');
    }
};
