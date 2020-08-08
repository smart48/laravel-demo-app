<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->string('slug')->nullable()->after('title');
            $table->longText('body',)->nullable()->after('slug');
            $table->string('image')->nullable()->after('body');
            $table->longText('excerpt')->nullable()->after('image');
            $table->string('active')->nullable()->after('excerpt');
            $table->string('user_id')->nullable()->after('active');
            $table->string('meta_description')->nullable()->after('user_id');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('seo_title')->nullable()->after('meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
}
