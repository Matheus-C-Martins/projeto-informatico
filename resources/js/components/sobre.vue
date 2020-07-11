<template>
  <v-app id="inspire">
    <nav>
      <v-app-bar app dense dark color="green" flat hide-on-scroll>
        <v-icon class="mr-2" @click="home()">{{ icons.mdiHome }}</v-icon>
        <v-spacer></v-spacer>
        <v-divider inset vertical dark></v-divider>
        <span>&nbsp; &nbsp;</span>
        <v-btn @click="cursos()" text>
          <v-toolbar-title> Cursos </v-toolbar-title>
        </v-btn>
        <span>&nbsp; &nbsp;</span>
        <v-divider inset vertical dark></v-divider>
        <span>&nbsp; &nbsp;</span>
        <v-btn @click="atividades()" text>
          <v-toolbar-title> Atividades </v-toolbar-title>
        </v-btn>
        <span>&nbsp; &nbsp;</span>
        <v-divider inset vertical dark></v-divider>
        <span>&nbsp; &nbsp;</span>
        <v-btn text>
          <v-toolbar-title> Sobre </v-toolbar-title>
        </v-btn>
        <span>&nbsp; &nbsp;</span>
        <v-divider inset vertical dark></v-divider>
        <v-spacer></v-spacer>
        <v-icon class="mr-2" @click="login()">{{ icons.mdiLogin }}</v-icon>
      </v-app-bar>
      <v-footer app inset absolute color="#FFFFFF">
        <v-row dense>
          <v-card tile flat width="100%">
            <v-card-text>
              <v-row align="end" justify="end">
                ©2020, feito por Matheus Martins e Ivan Silva
              </v-row>
            </v-card-text>
          </v-card>
        </v-row>
      </v-footer>
    </nav>
    <v-content v-if="loading === false" fluid class="mx-4">
      <v-row style="padding-top: 15px" align="center" justify="center">
        <v-hover v-slot:default="{ hover }">
          <v-card min-width="1500" max-width="1500" :elevation="hover ? 100 : 2" :class="{ 'on-hover': hover }">
            <div class="d-flex flex-no-wrap justify-space-between">
              <v-avatar tile size="150" class="ma-3">
                <v-img style="border-radius: 25%" aspect-ratio=1 src="/storage/Logo_DEI.png"></v-img>
              </v-avatar>
              <div>
                <v-card-title style="color:green;" class="mt-4 subheading text-left font-weight-bold"> DEI@Tour </v-card-title>
                <v-card-text>
                  <v-row dense justify="space-between">
                    <p class="mt-4 subheading text-left font-weight-light"> {{ texto }} </p>
                    <p class="mt-4 subheading text-left font-weight-light"> {{ texto2 }} </p>
                    <p class="mt-4 subheading text-left font-weight-light"> {{ texto3 }} </p>
                  </v-row>
                </v-card-text>
              </div>
            </div>
          </v-card>
        </v-hover>
      </v-row>
    </v-content>
    <v-content v-else fluid class="mx-4">
      <v-container style="widht:100%; height:100%; text-align:center" fluid>A carregar...</v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mdiLogin, mdiHome } from '@mdi/js';

export default {
  data() {
    return {
      loading: false,
      icons: { mdiLogin, mdiHome },
      texto: '',
      texto2: '',
      texto3: '',
    };
  },
  methods: {
    home() {
      this.$router.push("/");
    },
    login() {
      this.$router.push("/login");
    },
    cursos() {
      this.$router.push("/cursos");
    },
    atividades() {
      this.$router.push("/atividades");
    }
  },
  mounted() {
    this.loading = false;
    this.texto = "O DEI@Tour é uma plataforma web para auxiliar o Departamento de Engenharia Informática, do IPleiria, na preparação e\
                  gestão de atividades. As atividades que o Departamento de Engenharia Informática organiza são workshops de diversos temas, \
                  visitas à escola, seminários e ainda está presente no dia a aberto da Escola Superior de Engenharia e Gestão, do IPLeiria."
    this.texto2 = "Para além dessas atividades, o Departamento de Engenharia Informática, também proporciona uma academia de verão. Nesta academia de verão, \
                  os participantes terão aulas e workshops sobre temas relacionados com a Informática. Esta academia de verão, disponível para estudantes \
                  do ensino secundário é uma excelente oportunidade para desenvolver conhecimentos na área, ou ajudar na decisão sobre qual curso superior ingressar no futuro."
    this.texto3 = "Para se inscrever em qualquer uma das atividades contacte através do telefone: (+351) 244 820 300."
  },
};
</script>