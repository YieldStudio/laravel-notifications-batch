<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_notifications_batches', function (Blueprint $table) {
            $table->id();
            $table->string('channel');
            $table->longText('payload');
            $table->timestamps();
        });
    }
};
