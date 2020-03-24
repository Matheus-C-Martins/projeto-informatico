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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo', ['a', 'g']);
            $table->string('fotografia')->nullable();
        });

        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('Nome do Workshop', 100);
        });

        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->string('Nome');
            $table->string('Telefone Interno', 9);
            $table->string('Telemovel', 9);
            $table->string('E-mail', 50);
        });

        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->string('Nome');
            $table->text('Localizacao');
        });

        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('Nome');
            $table->string('Telefone', 9)->nullable();
            $table->string('E-mail', 50)->nullable();
            $table->enum('Sexo', ['masculino', 'feminino', 'outro']);
        });

        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Escola');
            $table->foreign('Escola')->references('id')->on('escolas');
            $table->string('Turma', 10);
            $table->string('Ano');
            $table->integer('Numero de Alunos');
            $table->dateTime('Data', 0);
            $table->time('Duracao');
            $table->unsignedBigInteger('Contacto');
            $table->foreign('Contacto')->references('id')->on('contactos');
            $table->text('Descricao');
            $table->enum('Tipo de Atividade', ['diaESTG', 'workshop']);
        });

        Schema::create('contactos_escolas', function (Blueprint $table) { // Contacto que nós recebemos
            $table->id();
            $table->unsignedBigInteger('Contacto');
            $table->foreign('Contacto')->references('id')->on('contactos');
            $table->enum('Tipo', ['Telefone', 'E-mail']);
            $table->unsignedBigInteger('Escola');
            $table->foreign('Escola')->references('id')->on('escolas');
            $table->text('Descricao')->nullable();
        });

        Schema::create('contactos_efetuados', function (Blueprint $table) { // Contacto que nós fazemos
            $table->id();
            $table->date('Data');
            $table->unsignedBigInteger('Contacto');
            $table->foreign('Contacto')->references('id')->on('contactos_escolas');
            $table->text('Descricao')->nullable();
            $table->enum('Tipo', ['Telefone', 'E-mail']);
        });

        Schema::create('workshops_atividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Atividade');
            $table->foreign('Atividade')->references('id')->on('atividades');
            $table->unsignedBigInteger('Workshop');
            $table->foreign('Workshop')->references('id')->on('workshops');
            $table->text('Descricao')->nullable();
        });

        Schema::create('docentes_atividade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Atividade');
            $table->foreign('Atividade')->references('id')->on('atividades');
            $table->unsignedBigInteger('Docente');
            $table->foreign('Docente')->references('id')->on('docentes');
            $table->text('Descricao Participacao')->nullable();
        });

        /* TABELAS SOBRE A ACADEMIA DE VERAO */
        Schema::create('atividades_a_verao', function (Blueprint $table) {
            $table->id();
            $table->string('Nome', 50);
            $table->enum('Tipo de Atividade', ['workshop', 'aula']);
            $table->text('Observacoes');
        });

        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->string('Nome', 15);
        });

        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            $table->string('Nome', 50);
            $table->unsignedBigInteger('Escola');
            $table->foreign('Escola')->references('id')->on('escolas');
            $table->string('Ano');
            $table->string('Curso');
            $table->date('Data de Nascimento');
            $table->string('Contacto do Aluno');
            $table->string('Contacto do EE');
            $table->string('Regiao');
        });

        Schema::create('academias_de_verao', function (Blueprint $table) {
            $table->id();
            $table->date('Data');
            $table->unsignedBigInteger('Participante');
            $table->foreign('Participante')->references('id')->on('participantes');
        });

        Schema::create('a_verao_docente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Atividade');
            $table->foreign('Atividade')->references('id')->on('atividades_a_verao');
            $table->unsignedBigInteger('Docente');
            $table->foreign('Docente')->references('id')->on('docentes');
            $table->unsignedBigInteger('Sala');
            $table->foreign('Sala')->references('id')->on('salas');
            $table->dateTime('Data', 0);
        });

        Schema::create('a_verao_participante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Atividade');
            $table->foreign('Atividade')->references('id')->on('atividades_a_verao');
            $table->unsignedBigInteger('Participante');
            $table->foreign('Participante')->references('id')->on('participantes');
            $table->dateTime('Data', 0);
        });
        /* ---------------------------------- */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('contactos_efetuados');
        Schema::dropIfExists('workshops_atividades');
        Schema::dropIfExists('docentes_atividade');
        Schema::dropIfExists('contactos_escolas');
        /* TABELAS SOBRE A ACADEMIA DE VERAO */
        Schema::dropIfExists('a_verao_participante');
        Schema::dropIfExists('a_verao_docente');
        Schema::dropIfExists('academias_de_verao');
        Schema::dropIfExists('salas');
        Schema::dropIfExists('participantes');
        Schema::dropIfExists('atividades_a_verao');
        /* ---------------------------------- */
        Schema::dropIfExists('atividades');
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('docentes');
        Schema::dropIfExists('escolas');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'fotografia']);
        });
    }
}
