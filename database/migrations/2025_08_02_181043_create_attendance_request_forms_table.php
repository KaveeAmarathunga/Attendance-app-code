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
    public function up(): void
    {
        Schema::create('attendance_request_forms', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('requested_epf_number', 20);
            $table->string('attendance_id', 20);
            $table->timestamps();

            // Foreign Key Constraint (assuming attendance table exists)
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_request_forms');
    }
};
