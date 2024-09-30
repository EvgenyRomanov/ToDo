<?php

use App\Models\Interfaces\RolesInterfaces;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $roles = [
        RolesInterfaces::ADMIN,
        RolesInterfaces::DEVELOPER,
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        $insertData = [];

        foreach ($this->roles as $role) {
            $insertData[] = ['name' => $role];
        }

        DB::table('roles')->insert($insertData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
