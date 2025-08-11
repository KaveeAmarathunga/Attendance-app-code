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
    Schema::create('leave_quantities', function (Blueprint $table) {
        $table->id();
        $table->string('leave_type', 20);
        $table->tinyInteger('leaves_for_executive');
        $table->tinyInteger('leaves_for_technicians');
        $table->tinyInteger('leaves_for_topmanagement');
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
        Schema::dropIfExists('leave_quantities');
    }
};
