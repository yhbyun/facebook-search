<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFbPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fb_posts', function(Blueprint $table)
		{
			$table->string('id');
            $table->bigInteger('from')->unsigned();
            $table->bigInteger('to')->unsigned();
            $table->text('message')->nullable();
            $table->string('full_picture')->nullable();
            $table->string('picture')->nullable();
            $table->string('link')->nullable();
            $table->string('name')->nullable();
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
			$table->timestamps();

            $table->primary('id');

            $table->foreign('from')
                ->references('id')->on('fb_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('fb_posts', function($table)
        {
            $table->dropForeign('fb_posts_from_foreign');
        });

		Schema::drop('fb_posts');
	}

}
