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
        Schema::create("page_builder_component_category_relations", function (
            Blueprint $table
        ) {
            $table->id();

            $table->integer("category_id");
            $table
                ->foreignId("component_id")
                ->references("id")
                ->on("page_builder_components")
                ->onDelete("cascade")
                ->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("page_builder_component_category_relations");
    }
};
