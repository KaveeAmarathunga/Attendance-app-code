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
        Schema::create('leaves', function (Blueprint $table) {
    $table->string('leave_id')->primary();
    $table->string('epf_number');
    $table->string('approved_by');
    $table->date('from_date');
    $table->date('to_date');
    $table->string('status'); // pending, approved, rejected
    $table->string('paid');
    $table->string('leavetype_id');
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
        Schema::dropIfExists('leaves');
    }
};
