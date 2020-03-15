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
            $table->enum('tipo', ['a', 'd', 'e']);
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
            $table->string('Telefone', 9);
            $table->string('E-mail', 50);
            $table->enum('Sexo', ['masculino','feminino','outro']);
        });

        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->foreign('Escola')->references('id')->on('escolas');
            $table->string('Turma', 10);
            $table->string('Ano');
            $table->integer('Numero de Alunos');
            $table->date('Data');
            $table->time('Duracao');
            $table->foreign('Contacto')->references('id')->on('contactos');
            $table->text('Descricao');
            $table->foreign('Docente')->references('id')->on('docentes')->nullable();
            $table->enum('Tipo de Atividade', ['dia aberto', 'workshop']);
        });

        Schema::create('contactos_escolas', function (Blueprint $table) { // Contacto que nós recebemos
            $table->id();
            $table->foreign('Contacto')->references('id')->on('contactos');
            $table->enum('Tipo', ['dia aberto','workshop']);
            $table->foreign('Escola')->references('id')->on('escolas');
            $table->text('Descricao')->nullable();
        });

        Schema::create('contactos_efetuados', function (Blueprint $table) { // Contacto que nós fazemos
            $table->id();
            $table->date('Data');
            $table->foreign('Contacto')->references('id')->on('contactos');
            $table->text('Descricao')->nullable();
            $table->enum('Tipo', ['dia aberto','workshop']);
            $table->boolean('Realizado', ['dia aberto','workshop']);
        });

        Schema::create('workshops_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreign('Atividade')->references('id')->on('atividade_workshop');
            $table->foreign('Workshop')->references('id')->on('workshops');
            $table->text('Descricao')->nullable();
        });

        Schema::create('docentes_atividade', function (Blueprint $table) {
            $table->id();
            $table->foreign('Docente')->references('id')->on('docentes');
            $table->foreign('Atividade')->references('id')->on('atividades');
            $table->text('Descricao Participacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('docentes');
        Schema::dropIfExists('escolas');
        Schema::dropIfExists('atividades');
        Schema::dropIfExists('contactos_escolas');
        Schema::dropIfExists('contactos_efetuados');
        Schema::dropIfExists('workshops_atividades');
        Schema::dropIfExists('docentes_atividade');
        Schema::dropIfExists('contactos');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'fotografia']);
        });
    }
}
