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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Laravel default primary key
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('epf_number', 20)->unique();

            // Foreign keys
            $table->string('company_id', 20);
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('usertype_id', 20);
            $table->foreign('usertype_id')->references('id')->on('usertypes');

            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('users');

            // Other fields
            $table->boolean('insurance');
            $table->string('blood_type', 20);
            $table->boolean('b_card_status');
            $table->date('date_of_append');
            $table->date('date_of_resign');
            $table->date('date_of_birth');
            $table->string('office_phonenumber', 20);
            $table->string('personal_phonenumber', 20);
            $table->string('emergency_phonenumber', 20);

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
