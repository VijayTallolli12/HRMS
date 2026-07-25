<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->index('name');
            $table->unique(['organization_id', 'name']);
            $table->string('status', 20)->default('active')->after('phone');
            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropUnique(['organization_id', 'name']);
            $table->dropColumn('status');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
