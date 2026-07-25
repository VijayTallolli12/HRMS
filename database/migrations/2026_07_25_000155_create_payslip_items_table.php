<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payslip_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payslip_id');
            $table->unsignedBigInteger('salary_component_id')->nullable();
            $table->string('component_name');
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->foreign('payslip_id')->references('id')->on('payslips')->onDelete('cascade');
            $table->foreign('salary_component_id')->references('id')->on('salary_components')->onDelete('set null');
            $table->index('payslip_id');
            $table->index('salary_component_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslip_items');
    }
};
