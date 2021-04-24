<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('primary_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('secondary_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('primary_category_id');

             // ここにカラムを追加していく

            $table->timestamps();

            // 今は外部キー制約を張らない。テストデータ等作成し終わったら制約を張る
            // $table->foreign('primary_category_id')->references('id')->on('primary_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secondary_categories');
        Schema::dropIfExists('primary_categories');
    }
}
