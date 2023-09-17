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
            $table->timestamp("deleted_at")->nullable();
            $table->integer("user_id")->unsigned();
            $table->string("title");
            $table->longText("html_code");
            $table->boolean("published");
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
