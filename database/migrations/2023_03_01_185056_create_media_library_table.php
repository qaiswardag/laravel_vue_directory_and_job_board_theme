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
            $table->integer("user_id")->unsigned();
            $table
                ->foreignId("team_id")
                ->references("id")
                ->on("teams")
                ->onDelete("cascade")
                ->constrained();

            $table->string("name")->nullable();
            $table->string("path")->unique();
            $table->string("thumbnail_path")->nullable();
            $table->string("medium_path")->nullable();
            $table->string("large_path")->nullable();
            $table->string("size");
            $table->string("width");
            $table->string("height");
            $table->string("extension");
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
