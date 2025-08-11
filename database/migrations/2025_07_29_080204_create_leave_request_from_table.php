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
    Schema::create('leave_requests', function (Blueprint $table) {
        $table->id(); // Auto-increment PK for ease (optional)
        $table->string('requested_epf_number', 20);
        $table->string('leave_id', 25);

        // Foreign key constraints (optional but recommended):
        // Assuming 'users' table has 'epf_number' column:
        $table->foreign('requested_epf_number')->references('epf_number')->on('users')->onDelete('cascade');
        
        // Assuming 'leave_type' table has 'leavetype_id':
        $table->foreign('leave_id')->references('leavetype_id')->on('leave_type')->onDelete('cascade');

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
        Schema::dropIfExists('leave_request_from');
    }
};
