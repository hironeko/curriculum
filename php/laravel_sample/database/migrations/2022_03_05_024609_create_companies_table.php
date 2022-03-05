<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('name_kana');
            $table->text('post_code');
            $table->text('prefecture');
            $table->text('address');
            $table->text('tel');
            $table->text('representative_first_name');
            $table->text('representative_last_name');
            $table->text('representative_first_name_kana');
            $table->text('representative_last_name_kana');
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
        Schema::dropIfExists('companies');
    }
}
