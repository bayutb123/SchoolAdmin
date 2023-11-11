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
        Schema::create('inventoryrequestgroup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->string('category');
            $table->string('name');
            $table->string('description')->nullabe();
            $table->decimal('price' ,10 ,2);
            $table->decimal('quantity', 5, 2);
            $table->string('quantity_unit')->nullabe();
            $table->string('status');
            $table->unsignedBigInteger('last_author_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('last_author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventoryrequestgroup');
    }
};
