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
        Schema::create('service_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');  // FK a Services
            $table->foreignId('user_enterprise_id')->constrained('user_enterprises')->onDelete('cascade');  // FK a users_enterprise
            $table->unique(['service_id', 'user_enterprise_id']);  // Evitar duplicaciones
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_collaborators');
    }
};
