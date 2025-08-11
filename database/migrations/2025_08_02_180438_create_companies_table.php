<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // database/migrations/xxxx_xx_xx_create_companies_table.php
Schema::create('companies', function (Blueprint $table) {
    $table->string('company_id')->primary();
    $table->string('name', 25);
    $table->string('location', 25);
    $table->string('address', 50);
    $table->tinyInteger('number_of_employees');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
