<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalaryToCedulaRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('cedula_requests', function (Blueprint $table) {
            $table->decimal('salary', 15, 2)->nullable()->after('weight'); // Adding salary column with precision 15, scale 2
        });
    }

    public function down()
    {
        Schema::table('cedula_requests', function (Blueprint $table) {
            $table->dropColumn('salary'); // Dropping salary column if rolling back
        });
    }
}
