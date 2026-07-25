<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index('tenant_id');
            $table->unique('name');
            $table->unique('tax_id');
            $table->unsignedBigInteger('created_by')->nullable()->after('meta');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('status', 20)->default('active')->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->dropUnique(['name']);
            $table->dropUnique(['tax_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('status');
        });
    }
};
