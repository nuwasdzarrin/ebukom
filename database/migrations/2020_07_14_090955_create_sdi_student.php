<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('nis')->nullable();
            $table->string('student_name')->nullable();
            $table->string('class_id')->nullable();
            $table->integer('gender_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sdi_student');
    }
}
