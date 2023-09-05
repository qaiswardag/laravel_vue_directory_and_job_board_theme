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
        Schema::create("stores", function (Blueprint $table) {
            $table->id();
            // user
            $table->integer("user_id")->unsigned();
            $table
                ->foreignId("team_id")
                ->references("id")
                ->on("teams")
                ->onDelete("cascade")
                ->constrained();
            //
            $table->boolean("show_author");
            $table->timestamp("deleted_at")->nullable();
            $table->string("title");
            $table->string("slug");
            $table->longText("content");
            $table->boolean("published");

            $table->string("cover_image_original")->nullable();
            $table->string("cover_image_thumbnail")->nullable();
            $table->string("cover_image_medium")->nullable();
            $table->string("cover_image_large")->nullable();

            $table->mediumText("tags")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("stores");
    }
};
