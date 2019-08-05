<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('selector');
            $table->string('verifier');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
        Schema::create('modified_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_segment');
            $table->string('secret_segment');
            $table->timestamp('claimed')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modified_tokens');
        Schema::dropIfExists('tokens');
    }
}