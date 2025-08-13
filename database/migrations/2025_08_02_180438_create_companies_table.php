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
    $table->id('company_id');
    $table->string('name', 25);
    $table->string('location', 25)->nullable();
    $table->string('address', 50)->nullable();
    $table->tinyInteger('number_of_employees')->default(0);
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
