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
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->string("id_number");
            $table->string("name");
            $table->string("email")->nullable();
            $table->string("phone_number")->nullable();
            $table->string("label")->nullable();
            $table->string("img_url")->nullable();
            $table->enum("level", ["TK", "SD", "SMP", "SMA", "SMK"]);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
