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
        Schema::table('live_meetings', function (Blueprint $table) {
            $table->string('meeting_id')->after('meta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('meeting_id')) {
            Schema::table('live_meetings', function (Blueprint $table) {
                $table->dropColumn('meeting_id');
            });
        }
    }
};
