<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToCedulaRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('cedula_requests', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->float('height', 5, 2)->nullable(); // Adjust precision and scale as needed
            $table->float('weight', 5, 2)->nullable(); // Adjust precision and scale as needed
            $table->string('profession_occupation_business')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('barangay_selection')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('tax_identification_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('cedula_requests', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'date_of_birth',
                'place_of_birth',
                'sex',
                'height',
                'weight',
                'profession_occupation_business',
                'civil_status',
                'barangay_selection',
                'citizenship',
                'tax_identification_number'
            ]);
        });
    }
}
