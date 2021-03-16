<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		//Schema::dropIfExists('plans');
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
			$table->string('name', 100);
			$table->string('price', 191);
			$table->string('currency')->default('INR');
			$table->longText('description');
			$table->enum('status', array('1', '0'));
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
        Schema::dropIfExists('plans');
    }
}
