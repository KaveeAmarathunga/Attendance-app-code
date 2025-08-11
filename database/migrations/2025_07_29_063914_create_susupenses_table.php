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
    Schema::create('suspenses', function (Blueprint $table) {
        $table->string('epf_number', 20);
        $table->tinyInteger('amount');
        
        // Assuming epf_number references users.epf_number or another table:
        $table->foreign('epf_number')->references('epf_number')->on('users')->onDelete('cascade');

        $table->timestamps(); // Optional, add if you want created_at/updated_at
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('susupenses');
    }
};
