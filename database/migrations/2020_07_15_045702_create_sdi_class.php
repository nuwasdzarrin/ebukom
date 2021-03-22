<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_class', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('class_name');
            $table->string('class_wali_id');
            $table->string('class_grade');
            $table->string('class_info');
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
        Schema::dropIfExists('sdi_class');
    }
}
