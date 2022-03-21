<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_forms', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('description')->nullable();
            $table->datetime('date_filed');
            $table->unsignedBigInteger('filed_doc_id');
            $table->foreign('filed_doc_id')->references('id')->on('filed_docs')->onDelete('cascade');
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
        Schema::dropIfExists('document_forms');
    }
}
