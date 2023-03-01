<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("media_libraries", function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("team_id");
            $table->string("name")->nullable();
            $table->string("path");
            $table->string("size");
            $table->string("width");
            $table->string("height");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("media_libraries");
    }
};
