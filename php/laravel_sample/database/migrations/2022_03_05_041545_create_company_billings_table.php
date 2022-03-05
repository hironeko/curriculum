<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->text('name');
            $table->text('name_kana');
            $table->text('post_code');
            $table->text('prefecture');
            $table->text('address');
            $table->text('tel');
            $table->text('department');
            $table->text('billing_first_name');
            $table->text('billing_last_name');
            $table->text('billing_first_name_kana');
            $table->text('billing_last_name_kana');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_billings');
    }
}
