<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('app_slug');
            $table->timestamps();

            $table->unique(['user_id', 'app_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_apps');
    }
};
