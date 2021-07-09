<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_master', function (Blueprint $table) {
            $table->bigIncrements('cm_id')->unsigned();
            $table->string('cm_firstname');
            $table->string('cm_lastname');
            $table->string('cm_photo');
            $table->string('cm_email');
            $table->string('cm_phone');
            $table->string('cm_notes');
            $table->integer('cm_am_id');
            $table->string('cm_is_active');
            $table->string('cm_type')->nullable();
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
        Schema::dropIfExists('client_master');
    }
}
