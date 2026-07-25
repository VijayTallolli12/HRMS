<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('employment_type_id')->nullable()->after('designation_id');
            $table->unsignedBigInteger('employee_category_id')->nullable()->after('employment_type_id');
            $table->unsignedBigInteger('employment_status_id')->nullable()->after('employee_category_id');
            $table->unsignedBigInteger('cost_center_id')->nullable()->after('employment_status_id');

            $table->foreign('employment_type_id')->references('id')->on('employment_types')->onDelete('set null');
            $table->foreign('employee_category_id')->references('id')->on('employee_categories')->onDelete('set null');
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('set null');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('set null');

            $table->index('employment_type_id');
            $table->index('employee_category_id');
            $table->index('employment_status_id');
            $table->index('cost_center_id');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['employment_type_id']);
            $table->dropForeign(['employee_category_id']);
            $table->dropForeign(['employment_status_id']);
            $table->dropForeign(['cost_center_id']);
            $table->dropIndex(['employment_type_id']);
            $table->dropIndex(['employee_category_id']);
            $table->dropIndex(['employment_status_id']);
            $table->dropIndex(['cost_center_id']);
            $table->dropColumn([
                'employment_type_id',
                'employee_category_id',
                'employment_status_id',
                'cost_center_id',
            ]);
        });
    }
};
