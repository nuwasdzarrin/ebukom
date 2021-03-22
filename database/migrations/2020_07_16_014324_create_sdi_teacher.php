<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_teacher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('nik')->nullable();
            $table->string('teacher_name')->nullable();
            $table->string('info')->nullable();
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
        Schema::dropIfExists('sdi_teacher');
    }
}
