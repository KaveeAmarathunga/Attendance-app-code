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
    $table->date('check_in');
    $table->date('check_out');
    $table->string('check_in_approved_by', 25);
    $table->integer('morning_allowence');
    $table->string('check_out_approved_by', 25);
    $table->integer('evening_allowence');
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
