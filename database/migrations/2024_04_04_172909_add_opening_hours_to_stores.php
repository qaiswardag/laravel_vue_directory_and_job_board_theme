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
        Schema::table("stores", function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("stores", function (Blueprint $table) {
            // Accepts time format 'HH:MM:SS'.
            // Example: To set the closing time to 6:00 PM (18:00),
            // send '18:00:00' from frontend to backend.

            $table->boolean("use_team_opening_hours")->nullable();

            $table->time("monday_opening_time")->nullable();
            $table->time("monday_closing_time")->nullable();

            $table->time("tuesday_opening_time")->nullable();
            $table->time("tuesday_closing_time")->nullable();

            $table->time("wednesday_opening_time")->nullable();
            $table->time("wednesday_closing_time")->nullable();

            $table->time("thursday_opening_time")->nullable();
            $table->time("thursday_closing_time")->nullable();

            $table->time("friday_opening_time")->nullable();
            $table->time("friday_closing_time")->nullable();

            $table->time("saturday_opening_time")->nullable();
            $table->time("saturday_closing_time")->nullable();

            $table->time("sunday_opening_time")->nullable();
            $table->time("sunday_closing_time")->nullable();

            $table->string("time_zone")->nullable(); // ->default("Asia/Dubai");
        });
    }
};
