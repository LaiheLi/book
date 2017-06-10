<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('catalog')->nullable();
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('path')->nullable();
            $table->string('cover')->nullable();
            $table->boolean('handle')->default(FALSE);
            $table->unsignedTinyInteger('type')->nullable();
            $table->timestamps();
            $table->index('catalog');
        });
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->string('name');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index('book_id');
        });
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('chapter_id')->nullable();
            $table->string('name');
            $table->string('path');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index('book_id');
            $table->index('chapter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sections');
        Schema::drop('chapters');
        Schema::drop('books');
    }
}
