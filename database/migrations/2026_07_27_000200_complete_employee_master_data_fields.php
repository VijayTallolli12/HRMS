<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('departments', 'branch_id')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('organization_id');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
                $table->index('branch_id');
            });
        }

        if (! Schema::hasColumn('departments', 'code')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->string('code', 50)->nullable()->after('name');
                $table->index('code');
            });
        }

        if (! Schema::hasColumn('departments', 'department_head_id')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->unsignedBigInteger('department_head_id')->nullable()->after('code');
                $table->foreign('department_head_id')->references('id')->on('employees')->onDelete('set null');
            });
        }

        if (! Schema::hasColumn('designations', 'branch_id')) {
            Schema::table('designations', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('organization_id');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
                $table->index('branch_id');
            });
        }

        if (! Schema::hasColumn('designations', 'grade')) {
            Schema::table('designations', function (Blueprint $table) {
                $table->string('grade', 50)->nullable()->after('title');
                $table->index('grade');
            });
        }
    }

    public function down(): void
    {
        // Compatibility migration only; base schema owns these columns on fresh installs.
    }
};
