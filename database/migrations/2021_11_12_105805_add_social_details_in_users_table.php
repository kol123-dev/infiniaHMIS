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
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_url', 100)->after('remember_token')->nullable();
            $table->string('twitter_url', 100)->after('facebook_url')->nullable();
            $table->string('instagram_url', 100)->after('twitter_url')->nullable();
            $table->string('linkedIn_url', 100)->after('instagram_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_url');
            $table->dropColumn('twitter_url');
            $table->dropColumn('instagram_url');
            $table->dropColumn('linkedIn_url');
        });
    }
};
