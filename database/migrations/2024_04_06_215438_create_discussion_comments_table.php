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
        Schema::create('discussion_comments', function (Blueprint $table) {
            $table->id();
            $table->longText("content");
            $table->integer("like_count");

            $table->unsignedBigInteger('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->onDelete('cascade')->onUpdate("cascade");

            $table->unsignedBigInteger('discussion_id');
            $table->foreign('discussion_id')->references('id')->on('discussions')->onDelete('cascade')->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussion_comments');
    }
};
