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
        Schema::create('attendances', function (Blueprint $table) {
    $table->string('attendance_id', 20)->primary();
    $table->string('epf_number', 20)->index();
    $table->date('date');
    $table->date('check_in')->nullable();
    $table->date('check_out')->nullable();
    $table->string('check_in_approved_by', 25)->nullable();
    $table->integer('morning_allowence')->nullable();
    $table->string('check_out_approved_by', 25)->nullable();
    $table->string('working_place', 20)->nullable();
    $table->string('site_number', 20)->nullable();
    $table->integer('evening_allowence')->nullable();
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
        Schema::dropIfExists('attendances');
    }
};
