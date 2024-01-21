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
        Schema::create('three_d_files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('image')->nullable();
            $table->string('filename');
            $table->unsignedBigInteger('subject_id');
            $table->enum('status',['pending','approved']);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('three_d_files');
    }
};
