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
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email")->unique();
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();

            $table->string("line1")->nullable();
            $table->string("line2")->nullable();

            $table->string("postal_code")->nullable();
            $table->string("phone_code")->nullable();

            $table->string("vat_id")->nullable();
            $table->string("tax_id")->nullable();
            $table->string("vat_number")->nullable();

            $table->string("phone")->nullable();
            $table->string("job_title")->nullable();
            $table
                ->string("username")
                ->unique()
                ->index();

            $table->longText("content")->nullable();
            $table->boolean("public")->nullable();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->foreignId("current_team_id")->nullable();
            $table->string("profile_photo_path", 2048)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("users");
    }
};
