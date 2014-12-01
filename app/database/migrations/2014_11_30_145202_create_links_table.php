<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('links', function(Blueprint $table) {
            $table->integer('link1')->unsigned();
            $table->integer('link2')->unsigned();
            $table->integer('weight')->unsigned();
            $table->timestamps();
            
            $table->primary(['link1', 'link2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('links');
    }

}
