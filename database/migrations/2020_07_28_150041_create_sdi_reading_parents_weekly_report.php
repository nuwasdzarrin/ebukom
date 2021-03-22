<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiReadingParentsWeeklyReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_reading_parents_weekly_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('student_id')->nullable();
            $table->string('komik')->nullable();
            $table->string('b_pelajaran')->nullable();
            $table->string('b_lainya')->nullable();
            $table->string('total_perday')->nullable();
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
        Schema::dropIfExists('sdi_reading_parents_weekly_report');
    }
}
