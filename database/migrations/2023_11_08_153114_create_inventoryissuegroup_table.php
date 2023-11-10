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
        Schema::create('inventoryissuegroup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_group_id');
            $table->unsignedBigInteger('inventory_id');
            $table->string('description');
            $table->string('solution');
            $table->string('status');
            $table->date('done_at');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inventory_group_id')->references('id')->on('inventoryissues')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventoryissuegroup');
    }
};
