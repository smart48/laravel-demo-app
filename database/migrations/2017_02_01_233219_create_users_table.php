<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->rememberToken();
			$table->enum('role', array('user', 'redac', 'admin'));
			$table->boolean('valid')->default(false);
			$table->boolean('confirmed')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}
