<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('profanity_subscribers', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('platform');
            $table->integer('platform_id');
            $table->integer('chat_id');
            $table->unique(['platform', 'platform_id', 'chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('profanity_subscribers');
    }
}
