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
            $table->string('nome', 100);
        });

        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone_interno', 9);
            $table->string('telemovel', 9);
            $table->string('email', 50);
        });

        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('localizacao');
        });

        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone', 9)->nullable();
            $table->string('email', 50)->nullable();
            $table->enum('sexo', ['masculino', 'feminino', 'outro']);
        });

        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escola');
            $table->foreign('escola')->references('id')->on('escolas');
            $table->string('turma', 10);
            $table->string('ano');
            $table->integer('numero_de_alunos');
            $table->dateTime('data', 0);
            $table->time('duracao');
            $table->unsignedBigInteger('contacto');
            $table->foreign('contacto')->references('id')->on('contactos');
            $table->text('descricao');
            $table->enum('tipo_de_atividade', ['diaESTG', 'workshop']);
        });

        Schema::create('contactos_escolas', function (Blueprint $table) { // Contacto que nós recebemos
            $table->id();
            $table->unsignedBigInteger('contacto');
            $table->foreign('contacto')->references('id')->on('contactos');
            $table->enum('tipo', ['Telefone', 'Email']);
            $table->unsignedBigInteger('escola');
            $table->foreign('escola')->references('id')->on('escolas');
            $table->text('descricao')->nullable();
        });

        Schema::create('contactos_efetuados', function (Blueprint $table) { // Contacto que nós fazemos
            $table->id();
            $table->date('data');
            $table->unsignedBigInteger('contacto');
            $table->foreign('contacto')->references('id')->on('contactos_escolas');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['Telefone', 'Email']);
        });

        Schema::create('workshops_atividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atividade');
            $table->foreign('atividade')->references('id')->on('atividades');
            $table->unsignedBigInteger('workshop');
            $table->foreign('workshop')->references('id')->on('workshops');
            $table->dateTime('data', 0);
            $table->text('descricao')->nullable();
        });

        Schema::create('docentes_atividade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atividade');
            $table->foreign('atividade')->references('id')->on('atividades');
            $table->unsignedBigInteger('docente');
            $table->foreign('docente')->references('id')->on('docentes');
            $table->text('descricao_participacao')->nullable();
        });

        /* TABELAS SOBRE A ACADEMIA DE VERAO */
        Schema::create('academias_de_verao', function (Blueprint $table) {
            $table->id();
            $table->date('data');
        });

        Schema::create('atividades_a_verao', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50);
            $table->enum('tipo', ['workshop', 'aula']);
            $table->text('observacoes');
            $table->unsignedBigInteger('academia_de_verao');
            $table->foreign('academia_de_verao')->references('id')->on('academias_de_verao');
        });

        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->enum('edificio', ['A', 'B', 'C', 'D', 'E', 'Biblioteca']);
            $table->string('nome', 15);
        });

        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50);
            $table->unsignedBigInteger('escola');
            $table->foreign('escola')->references('id')->on('escolas');
            $table->string('ano');
            $table->string('curso');
            $table->date('data_de_nascimento');
            $table->string('contacto_do_Aluno');
            $table->string('contacto_do_ee');
            $table->string('encarregado_de_educacao');
            $table->string('regiao');
            $table->unsignedBigInteger('academia_de_verao');
            $table->foreign('academia_de_verao')->references('id')->on('academias_de_verao');
        });

        Schema::create('a_verao_docente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atividade');
            $table->foreign('atividade')->references('id')->on('atividades_a_verao');
            $table->unsignedBigInteger('docente');
            $table->foreign('docente')->references('id')->on('docentes');
            $table->unsignedBigInteger('sala');
            $table->foreign('sala')->references('id')->on('salas');
            $table->dateTime('data', 0);
        });

        Schema::create('a_verao_participante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atividade');
            $table->foreign('atividade')->references('id')->on('atividades_a_verao');
            $table->unsignedBigInteger('participante');
            $table->foreign('participante')->references('id')->on('participantes');
            $table->dateTime('data', 0);
        });

        /* ---------------------------------- */

        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('abreviatura');
            $table->string('nome');
            $table->enum('tipo', ['Curso Técnico Superior Profissional', 'Licenciatura', 'Mestrado']);
            $table->integer('semestres');
            $table->string('ECTS');
            $table->string('vagas');
            $table->string('contato');
            $table->string('objetivos', 1000);
        });

        Schema::create('inqueritos', function (Blueprint $table){
            $table->id();
            $table->dateTime('data', 0);
            $table->integer('codigo_acesso');
            $table->enum('estado_codigo', ['Ativo', 'Inativo']);
            $table->enum('tipo1', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta1');
            $table->enum('tipo2', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta2');
            $table->enum('tipo3', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta3');
            $table->enum('tipo4', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta4');
            $table->enum('tipo5', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta5');
            $table->enum('tipo6', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta6');
            $table->enum('tipo7', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta7');
            $table->enum('tipo8', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta8');
            $table->enum('tipo9', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta9');
            $table->enum('tipo10', ['Escolha única', 'Resposta Aberta']);
            $table->string('pergunta10');
        });

        Schema::create('respostas_inqueritos', function (Blueprint $table){
            $table->id();
            $table->dateTime('data', 0);
            $table->string('resposta1');
            $table->string('resposta2');
            $table->string('resposta3');
            $table->string('resposta4');
            $table->string('resposta5');
            $table->string('resposta6');
            $table->string('resposta7');
            $table->string('resposta8');
            $table->string('resposta9');
            $table->string('resposta10');
            $table->unsignedBigInteger('inquerito_id');
            $table->foreign('inquerito_id')->references('id')->on('inqueritos');
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
        Schema::dropIfExists('participantes');
        Schema::dropIfExists('salas');
        Schema::dropIfExists('atividades_a_verao');
        Schema::dropIfExists('academias_de_verao');
        /* ---------------------------------- */
        Schema::dropIfExists('atividades');
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('docentes');
        Schema::dropIfExists('escolas');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'fotografia']);
        });
        /* ---------------------------------- */
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('inqueritos');
        Schema::dropIfExists('respostas_inqueritosS');
    }
}
