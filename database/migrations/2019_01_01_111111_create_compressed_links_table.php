<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompressedLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compressed_links', function (Blueprint $table) {
            $table->collation = 'utf8_bin';
            $table->charset = 'utf8';
            $table->increments('id');
            $table->integer('user_id')->unsigned();;
            $table->string('link', 2000);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compressed_links');
    }
}
