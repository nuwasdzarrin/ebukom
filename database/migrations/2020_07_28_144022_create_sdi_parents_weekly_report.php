<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiParentsWeeklyReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_parents_weekly_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('student_id')->nullable();
            $table->string('bangun_pagi')->nullable();
            $table->string('mandiri')->nullable();
            $table->string('subuh')->nullable();
            $table->string('dhuhur')->nullable();
            $table->string('ashar')->nullable();
            $table->string('magrib')->nullable();
            $table->string('isya')->nullable();
            $table->string('mendoakan')->nullable();
            $table->string('patuh')->nullable();
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
        Schema::dropIfExists('sdi_parents_weekly_report');
    }
}
