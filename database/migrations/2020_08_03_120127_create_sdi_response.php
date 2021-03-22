<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiResponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_response', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('parents_report_id')->nullable();
            $table->integer('weekly_report_id')->nullable();
            $table->string('information')->nullable();
            $table->string('response')->nullable();
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
        Schema::dropIfExists('sdi_response');
    }
}
