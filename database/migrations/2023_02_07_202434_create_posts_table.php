<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("posts", function (Blueprint $table) {
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
            //
            $table->timestamp("started_at")->nullable()->index();
            $table->timestamp("ended_at")->nullable()->index();
            $table->boolean("is_paid")->nullable();
            $table->timestamp("paid_at")->nullable();
            //
            //
            $table->string("title")->index();
            $table->string("slug");
            $table->boolean("published")->nullable();
            $table->boolean("featured")->nullable();
            $table->longText("content");
            $table
                ->string("tags")
                ->nullable()
                ->index();

            $table->timestamps();
            $table->timestamp("deleted_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("posts");
    }
};
