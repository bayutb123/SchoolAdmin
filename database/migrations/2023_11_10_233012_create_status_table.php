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
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('color')->default('primary');
            $table->timestamps();
        });

        $status = [
            [
                'type' => 'inventory',
                'name' => 'Baik',
                'color' => 'success',
            ],
            [
                'type' => 'inventory',
                'name' => 'Kurang',
                'color' => 'warning',
            ],
            [
                'type' => 'inventory',
                'name' => 'Tidak layak',
                'color' => 'danger',
            ],
            [
                'type' => 'inventory',
                'name' => 'Tidak diketahui',
                'color' => 'secondary',
            ],
            [
                'type' => 'issue',
                'name' => 'Dalam Perbaikan',
                'color' => 'secondary',
            ],
            [
                'type' => 'issue',
                'name' => 'Pending',
                'color' => 'secondary',
            ],
        ];

        DB::table('status')->insert($status);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
