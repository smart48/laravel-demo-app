<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration {

	public function up()
	{
		Schema::create('tags', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('tag', 50)->unique();
		});
	}

	public function down()
	{
		Schema::drop('tags');
	}
}
