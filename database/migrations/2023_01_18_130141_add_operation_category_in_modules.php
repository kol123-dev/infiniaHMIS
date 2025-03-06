<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Module::create([
            'name' => 'Operation Categories',
            'is_active' => 1,
            'route' => 'operation.category.index',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Module::where('name', 'Operation Categories')->delete();
    }
};
