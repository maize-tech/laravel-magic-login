<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('magic_logins', function (Blueprint $table) {
            $table->uuid();
            $table->morphs('authenticatable');
            $table->integer('logins')->default(0);
            $table->integer('logins_limit');
            $table->string('guard');
            $table->string('redirect_url');
            $table->datetime('expires_at');
            $table->json('metadata');
            $table->timestamps();
        });
    }
};
