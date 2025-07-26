<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('onsens', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // 温泉名
            $table->text('description')->nullable(); // 説明
            $table->string('prefecture');      // 都道府県
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('image_path')->nullable();
            $table->string('open_time')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onsens');
    }
};
