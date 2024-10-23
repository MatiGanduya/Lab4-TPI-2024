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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['email', 'sms']);  // Campo ENUM para el tipo de notificación
            $table->string('text');  // Mensaje de la notificación
            $table->dateTime('sent_at');  // Fecha y hora de envío
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // FK a Users
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');  // FK a Appointments
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
