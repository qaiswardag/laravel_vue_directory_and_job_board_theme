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
        Schema::table("subscriptions", function (Blueprint $table) {
            $table
                ->foreignId("team_id")
                ->nullable()
                ->after("user_id")
                ->references("id")
                ->on("teams")
                ->onDelete("set null") // Set team_id to NULL when the corresponding team is deleted
                ->constrained();

            $table
                ->string("updated_subscription_name")
                ->nullable()
                ->after("name");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("subscriptions", function (Blueprint $table) {
            $table->dropColumn("updated_subscription_name");
        });
    }
};
