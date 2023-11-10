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
        // add new column named role
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();
            $table->unsignedBigInteger('role_id')->after('id')->default(2);

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        $user = new App\Models\User();
        $user->name = 'Administrator';
        $user->email = 'admin@localhost';
        $user->password = bcrypt('admin');
        $user->role_id = 1;
        $user->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop the foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        // drop the column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
};
