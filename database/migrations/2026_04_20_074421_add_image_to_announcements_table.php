<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->text('content')->nullable()->change();
            $table->string('image')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('announcements')->whereNull('content')->update(['content' => '']);

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->text('content')->nullable(false)->change();
        });
    }
};
