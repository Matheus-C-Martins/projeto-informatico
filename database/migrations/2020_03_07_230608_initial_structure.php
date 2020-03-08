<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialStructure extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('Nome do Workshop', 100);
        });

        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->string('Escola');
            $table->string('Turma', 10);
            $table->string('Ano');             // Ano Escolar?
            $table->string('Numero de Aluno'); // ??
            $table->date('Data');
            $table->time('Hora de Inicio');
            $table->time('Hora de Fim');
            $table->enum('Tipo de Atividade', ['dia aberto','workshop']);            
        });

        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->string('Nome');
            $table->string('Telefone Interno', 9);
            $table->string('Telemovel', 9);
            $table->string('E-mail', 50);     
        });

        Schema::create('workshops_atividades', function (Blueprint $table) {
            $table->foreign('atividade')->references('id')->on('atividade_workshop');
            $table->foreign('workshop')->references('id')->on('workshops');
            $table->text('Descricao')->nullable();
        });

        Schema::create('docentes_atividade', function (Blueprint $table) {
            $table->foreign('docente')->references('id')->on('docentes');
            $table->foreign('atividade')->references('id')->on('atividades');
            $table->text('Descricao Participacao')->nullable();
        });

        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('Nome');
            $table->string('Telefone', 9);
            $table->string('E-mail', 50);
            $table->enum('Sexo', ['masculino','feminino','outro']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('workshops_atividades');
        Schema::dropIfExists('atividade_dia_estg');
        Schema::dropIfExists('contactos');
    }
}
