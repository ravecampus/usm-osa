<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiledDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filed_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('college_department_id');
            $table->foreign('college_department_id')->references('id')->on('college_departments')->onDelete('cascade');
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('orgs')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filed_docs');
    }
}
