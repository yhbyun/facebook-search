<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFbLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fb_likes', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
            $table->bigInteger('fb_user_id')->unsigned();
            $table->string('fb_post_id');
            $table->timestamps();

            $table->foreign('fb_user_id')
                ->references('id')->on('fb_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('fb_post_id')
                ->references('id')->on('fb_posts')
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
        Schema::table('fb_likes', function($table)
        {
            $table->dropForeign('fb_likes_fb_post_id_foreign');
            $table->dropForeign('fb_likes_fb_user_id_foreign');
        });

		Schema::drop('fb_likes');
	}

}
