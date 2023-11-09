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
            $table->integer('inventory_group_id');
            $table->string('name');
            $table->string('category');
            $table->decimal('quantity', 5, 2);
            $table->string('quantity_unit')->nullabe();
            $table->decimal('price' ,10 ,2);
            $table->decimal('total' ,15 ,2);
            $table->string('status');
            $table->date('purchased_at')->nullabe();
            $table->string('note')->nullabe();
            $table->timestamps();
            $table->softDeletes();

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
