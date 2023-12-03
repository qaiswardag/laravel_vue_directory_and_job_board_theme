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
        Schema::create('post_store_relations', function (Blueprint $table) {
            $table->id();

            $table->integer("store_id");
            $table
                ->foreignId("post_id")
                ->references("id")
                ->on("posts")
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
        Schema::dropIfExists('post_store_relations');
    }
};
