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
        Schema::create('cedula_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status', 20)->default('ON PROCESS');
            $table->longText('type');
            $table->longText('staff_comment')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('request_uuid');
            $table->date('pick_up_date')->nullable();
            $table->time('pick_up_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cedula_requests');
    }
};
