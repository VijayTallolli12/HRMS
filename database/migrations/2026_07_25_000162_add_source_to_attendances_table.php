<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('source')->default('manual')->after('notes');
            $table->unsignedBigInteger('import_batch_id')->nullable()->after('source');
            $table->foreign('import_batch_id')->references('id')->on('attendance_import_batches')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['import_batch_id']);
            $table->dropColumn(['source', 'import_batch_id']);
        });
    }
};
