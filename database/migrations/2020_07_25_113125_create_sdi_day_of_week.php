<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSdiDayOfWeek extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdi_day_of_week', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('day_name')->nullable();
        });

        DB::table('sdi_day_of_week')->insert(
            [
                ['day_name' => 'Senin'],
                ['day_name' => 'Selasa'],
                ['day_name' => 'Rabu'],
                ['day_name' => 'Kamis'],
                ['day_name' => 'Jumat'],
                ['day_name' => 'Sabtu']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sdi_day_of_week');
    }
}
