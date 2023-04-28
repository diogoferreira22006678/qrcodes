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
        Schema::create('docs', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('doc_name');
            $table->string('doc_path');
            $table->string('doc_observation', 200)->nullable();
            $table->unsignedBigInteger('folder_id');

            $table->foreign('folder_id')->references('folder_id')->on('folders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docs');
    }
};
