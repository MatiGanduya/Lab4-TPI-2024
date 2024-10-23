<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('duration'); // Añade la columna empresa_id
            // Si necesitas definir la relación con la tabla de empresas, descomenta la línea siguiente
            // $table->foreign('empresa_id')->references('id')->on('enterprises')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('empresa_id');
        });
    }
};
