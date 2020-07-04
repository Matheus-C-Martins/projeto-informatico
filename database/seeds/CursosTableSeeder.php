<?php

use Illuminate\Database\Seeder;

class CursosTableSeeder extends Seeder {
    public function run() {
        $this->command->table(['Cursos table seeder notice'], [
            ['Photos will be stored on path '.storage_path('app\public\logoCursos')]
        ]);

        $this->limparFicheirosFotos();

        DB::table('tipo_cursos')->insert([
        [
            'id' => 1,
            'nome' => 'Curso Técnico Superior Profissional'
        ], 
        [
            'id' => 2,
            'nome' => 'Licenciatura'
        ],
        [
            'id' => 3,
            'nome' => 'Mestrado'
        ]]);
        $this->command->info("Tipos de cursos criados.");

        DB::table('cursos')->insert([
        [
            'abreviatura' => 'TESP-DWM',
            'nome' => 'Desenvolvimento Web e Multimédia',
            'tipo' => 1,
            'semestres' => 4,
            'ECTS' => 120,
            'vagas' => 45,
            'contato' => 'coord.dwm.tesp.estg@ipleiria.pt',
            'objetivos' => 'O curso Técnico Superior Profissional em Desenvolvimento Web e Multimédia visa formar profissionais aptos para conceber e implementar aplicações e serviços Web com conteúdos multimédia, autonomamente ou em equipa, recorrendo às novas tecnologias e ferramentas.',
            'fotografia' => 'TESP-DWM.jpg'
        ], 
        [
            'abreviatura' => 'TESP-PSI',
            'nome' => 'Programação de Sistemas de Informação',
            'tipo' => 1,
            'semestres' => 4,
            'ECTS' => 120,
            'vagas' => 40,
            'contato' => 'coord.psi.tesp.estg@ipleiria.pt',
            'objetivos' => 'O curso Técnico Superior Profissional em Programação de Sistemas de Informação visa formar profissionais aptos a programar e integrar sistemas, aplicações e serviços de base heterogénea, recorrendo a tecnologias, ferramentas e metodologias de desenvolvimento de software modernas.',
            'fotografia' => 'TESP-PSI.jpg'
        ],
        [
            'abreviatura' => 'TESP-RSI',
            'nome' => 'Redes e Sistemas Informáticos',
            'tipo' => 1,
            'semestres' => 4,
            'ECTS' => 120,
            'vagas' => 40,
            'contato' => 'coord.rsi.tesp.estg@ipleiria.pt',
            'objetivos' => 'O curso Técnico Superior Profissional em Redes e Sistemas Informáticos visa formar profissionais aptos para realizar tarefas de planeamento, instalação, configuração, gestão e manutenção de redes e sistemas informáticos, garantindo a otimização do seu funcionamento e procedendo à implementação de mecanismos de segurança adequados.',
            'fotografia' => 'TESP-RSI.jpg'
        ],
        [
            'abreviatura' => 'TESP-TI',
            'nome' => 'Tecnologias Informáticas',
            'tipo' => 1,
            'semestres' => 4,
            'ECTS' => 120,
            'vagas' => 18,
            'contato' => 'coord.ti.tesp.estg@ipleiria.pt',
            'objetivos' => 'O curso Técnico Superior Profissional em Tecnologias Informáticas visa formar profissionais que, de forma autónoma ou em equipa, tenham a capacidade para conceber, modelar, implementar e manter sistemas de informação de suporte às organizações, tendo como base as tecnologias informáticas emergentes e as existentes no mercado, de modo a permitir a otimização dos processos de negócio das organizações. O profissional com esta formação deverá ter um nível elevado de especialização em tecnologias digitais e aplicações, nomeadamente SAP, OutSystems, Navision, Java, RPA (Robotic Process Automation), entre outras, permitindo a criação de maior valor acrescentado nas organizações e na economia. O currículo académico do curso será muito prático, permitindo aos estudantes trabalhar em ambiente real, o que significa, executar atividades e integrar equipas de pequenos projetos. O seu tempo será dividido entre as atividades letivas e a vertente prática, em ambiente real.',
            'fotografia' => 'TESP-TI.jpg'
        ],
        [
            'abreviatura' => 'EI',
            'nome' => 'Engenharia Informática',
            'tipo' => 2,
            'semestres' => 6,
            'ECTS' => 180,
            'vagas' => 100,
            'contato' => 'coord.ei.estg@ipleiria.pt',
            'objetivos' => 'A licenciatura em Engenharia Informática tem por objetivo formar profissionais com competências nas áreas dos Sistemas de Informação e das Tecnologias de Informação e Comunicação. A licenciatura prepara os estudantes para o desenvolvimento de software, planeamento e configuração de redes e serviços, segurança, computação móvel, administração e análise inteligente de dados, entre outras valências. Durante o curso, os estudantes têm ainda oportunidade de implementar projetos de casos reais com entidades internas e externas. Os licenciados em Engenharia Informática têm elevada empregabilidade, sendo procurados por empresas de topo.',
            'fotografia' => 'EI.jpg'
        ],
        [
            'abreviatura' => 'JDM',
            'nome' => 'Jogos Digitais e Multimédia',
            'tipo' => 2,
            'semestres' => 6,
            'ECTS' => 180,
            'vagas' => 44,
            'contato' => 'coord.jdm.estg@ipleiria.pt',
            'objetivos' => 'Formar profissionais para as áreas dos videojogos e da produção multimédia. Os licenciados terão as competências necessárias para trabalhar nas diversas fases de construção de um jogo digital (conceção, programação, criação artística, quality assurance), assim como em outros tipos de produção multimédia (software interativo, aplicações web, aplicações móveis, etc.).',
            'fotografia' => 'JDM.jpg'
        ],
        [
            'abreviatura' => 'MEI-CM',
            'nome' => 'Engenharia Informática-Computação Móvel',
            'tipo' => 3,
            'semestres' => 4,
            'ECTS' => 120,
            'vagas' => 25,
            'contato' => 'coord.mei-cm.estg@ipleiria.pt',
            'objetivos' => 'O Mestrado em Engenharia Informática – Computação Móvel (MEI-CM) tem como objetivo desenvolver uma formação especializada na área da computação móvel e tecnologias associadas, permitindo a prossecução de estudos a titulares de licenciaturas em Engenharia Informática ou cursos relacionados e a possibilidade de especialização para os profissionais do mercado de trabalho. O curso assenta no ensino baseado em projetos com vista a dotar os diplomados de capacidade de trabalho em equipa, planeamento e organização do trabalho, pesquisa e aquisição do conhecimento necessário, bem como o desenvolvimento da autonomia, iniciativa, análise crítica e avaliação de soluções.',
            'fotografia' => 'MEI-CM.jpg'
        ],
        [
            'abreviatura' => 'MCIF',
            'nome' => 'Cibersegurança e Informática Forense',
            'tipo' => 3,
            'semestres' => 4,
            'ECTS' => 120,
            'vagas' => 15,
            'contato' => 'coord.mcif.estg@ipleiria.pt',
            'objetivos' => 'O Mestrado em Cibersegurança e Informática Forense (MCIF) tem como objetivo desenvolver uma formação especializada nas áreas da cibersegurança e da computação forense, permitindo a prossecução de estudos a titulares de licenciaturas em Engenharia Informática ou cursos relacionados e formando técnicos altamente qualificados nas área da cibersegurança e da informática forense. Este mestrado tem ainda como objetivo desenvolver a ligação com o tecido empresarial da região através da realização de estágios, projetos e dissertações, que possam permitir a aplicação de boas práticas na área da cibersegurança. Constituem ainda objetivos primordiais deste mestrado o desenvolvimento da investigação aplicada nas áreas da cibersegurança e computação forense, assim como a promoção da transferência de conhecimento avançado nas áreas de cibersegurança e computação forense para as organizações.',
            'fotografia' => 'MCIF.jpg'
        ]]);
        $this->command->info("Cursos criados.");

        $this->gravarFoto();

        DB::table('info_atividades')->insert([
            [
                'id' => 1,
                'nome' => 'Dia ESTG',
                'abreviatura' => 'Um dia na ESTG',
                'descricao' => 'A ESTG disponibiliza a todos os estudantes dos ensinos básico e secundário (9º ao 12º ano) a atividade Um dia @ ESTG. Trata-se de uma atividade em que convidamos grupos de 1 a 5 estudantes a virem à ESTG para que passem um dia com estudantes desta Escola de Ensino Superior, tendo a oportunidade de conhecer o(s) curso(s) de Licenciatura, ou área científica, em que tenham particular interesse assistir às respetivas aulas, visitar salas, laboratórios, a Biblioteca e todo o Campus, desenvolvendo estas atividades com uma rotina diária idêntica à de um estudante da ESTG. Aos visitantes é assim dada a oportunidade de, ativamente e inseridos na Comunidade Académica, conhecer a realidade da Escola, os seus cursos e o seu funcionamento. A data é marcada em função da disponibilidade e interesse dos visitantes. Todas as visitas são ajustadas aos interesses e necessidades dos estudantes visitantes, abarcando diferentes áreas, tais como: Engenharia, Ciências Empresariais e Ciências Jurídicas Aplicadas.'
            ], 
            [
                'id' => 2,
                'nome' => 'Workshop',
                'abreviatura' => 'Formação prática',
                'descricao' => 'Trata-se de uma forma de treino, similar a um curso de curta duração, que procura aprofundar sobre um determinado tema, o qual foi prefixado pelos mediadores, que visam a produtividade e a prática.'
            ],
            [
                'id' => 3,
                'nome' => 'Seminário',
                'abreviatura' => 'Formação teórica',
                'descricao' => 'O seminário é uma reunião especializada, de natureza técnica ou acadêmica, que procura levar a cabo estudos aprofundados sobre uma determinada matéria. Um seminário tem como principal objetivo a transmissão do conhecimento, conhecimento esse que pode acontecer por meio da apresentação de informações atualizadas sobre um tema, da apresentação de dados atuais, da troca de ideias e opiniões.'
            ],
            [
                'id' => 4,
                'nome' => 'Visita à escola',
                'abreviatura' => 'Dia aberto',
                'descricao' => 'A ESTG abre as portas a todos aqueles que pretendem conhecer de perto a realidade da Escola, enquanto instituição de ensino superior, mostrando as ofertas de cursos, os trabalhos desenvolvidos bem como os projetos de fim de curso. Os visitantes são convidados a conhecer, experimentar e explorar a ESTG, através de divertidas experiências laboratoriais, jogos e exposições. A ESTG transforma-se num infindável campo de descoberta, conhecimento e saber.'
            ]]);
            $this->command->info("Info de atividades criada.");
    }

    private function limparFicheirosFotos() {
        Storage::deleteDirectory('public\logoCursos');
        Storage::makeDirectory('public\logoCursos');
    }

    private function gravarFoto() {
        $targetDir = storage_path('app\public\logoCursos');

        $allFiles = collect(File::files(database_path('seeds\logoCursos')));
        foreach ($allFiles as $f) {
            File::copy($f->getPathname(), $targetDir."\\".$f->getFilename());
            $this->command->info("Foto ".$f->getPathname()." copiada.");
        }
    }
}