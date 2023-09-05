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
        Schema::create("page_builder_components", function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->longText("html_code");

            $table->string("cover_image_original");
            $table->string("cover_image_thumbnail");
            $table->string("cover_image_medium");
            $table->string("cover_image_large");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("page_builder_components");
    }
};
