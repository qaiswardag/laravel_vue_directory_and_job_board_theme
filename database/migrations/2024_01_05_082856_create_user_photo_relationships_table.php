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
        Schema::create("user_photo_relationships", function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId("media_library_id")
                ->references("id")
                ->on("media_library_users")
                ->onDelete("cascade")
                ->constrained();

            $table
                ->foreignId("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->constrained();
            $table->boolean("primary")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_photo_relationships");
    }
};
