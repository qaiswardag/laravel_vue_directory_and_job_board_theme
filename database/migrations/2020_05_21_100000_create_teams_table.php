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

            $table
                ->foreignId("user_id")
                ->index()
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->constrained();
            //
            $table->string("name");
            $table
                ->string("address")
                ->nullable()
                ->index();

            $table->string("contact_page_url")->nullable();

            $table
                ->string("slug")
                ->unique()
                ->index();

            $table->longText("content")->nullable();

            $table->boolean("public")->nullable();
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
