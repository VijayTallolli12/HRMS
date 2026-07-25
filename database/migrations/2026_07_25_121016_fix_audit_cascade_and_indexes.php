<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('organization_id');
            $table->index('branch_id');
            $table->index('department_id');
            $table->index('designation_id');
        });

        Schema::table('attendance_adjustments', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('overtime_requests', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['organization_id']);
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['designation_id']);
        });

        Schema::table('attendance_adjustments', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('overtime_requests', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
