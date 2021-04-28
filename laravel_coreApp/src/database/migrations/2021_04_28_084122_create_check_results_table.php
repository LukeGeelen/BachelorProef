<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id');
            $table->foreignId('check_id');
            $table->string('effective_output')->nullable();
            $table->boolean('check_passed')->nullable();
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
        Schema::dropIfExists('check_results');
    }
}
