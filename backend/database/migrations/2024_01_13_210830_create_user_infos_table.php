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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name', 50);
            $table->string('middle_name', 50);
            $table->string('surname', 50);
            $table->string('phone_number')->unique()->nullable();
            $table->string('sex', 10)->nullable();
            $table->string('civil_status', 25)->nullable();
            $table->string('citizenship', 50)->nullable();
            $table->string('date_of_birth', 20)->nullable();
            $table->string('place_of_birth', 100)->nullable();
            $table->longText('profession_occupation')->nullable();
            $table->longText('address')->nullable();
            $table->string('tax_identification_number', 20)->unique()->nullable();
            $table->string('baranggay', 100)->nullable();
            $table->string('height', 10)->nullable();
            $table->string('weight', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
