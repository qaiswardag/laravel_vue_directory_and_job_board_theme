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
        Schema::create("teams", function (Blueprint $table) {
            $table->id();
            // $table->foreignId("user_id")->index(); // original row structure
            //
            $table
                ->string("reference_id")
                ->unique()
                ->index();

            $table
                ->foreignId("user_id")
                ->index()
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->constrained();
            //
            $table->string("name");

            $table->string("cover_image_original")->nullable();
            $table->string("cover_image_thumbnail")->nullable();
            $table->string("cover_image_medium")->nullable();
            $table->string("cover_image_large")->nullable();

            $table->string("logo_original")->nullable();
            $table->string("logo_thumbnail")->nullable();
            $table->string("logo_medium")->nullable();
            $table->string("logo_large")->nullable();

            $table->boolean("public");
            $table->boolean("personal_team");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("teams");
    }
};
