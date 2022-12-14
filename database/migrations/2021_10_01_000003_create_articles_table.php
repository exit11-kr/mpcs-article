<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('article_category_id')->unsigned()->index();
            $table->string('title', 100);
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->LongText('markdown')->nullable();
            $table->LongText('html')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('view_count')->unsigned()->default(0);
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
