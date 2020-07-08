<template>
  <v-app id="inspire">
    <nav>
      <v-app-bar app dense dark color="green" flat hide-on-scroll>
        <v-icon class="mr-2" @click="home()">{{ icons.mdiHome }}</v-icon>
        <v-spacer></v-spacer>
        <v-divider inset vertical dark></v-divider>
        <span>&nbsp; &nbsp;</span>
        <v-btn text>
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
        <v-btn @click="sobre()" text>
          <v-toolbar-title> Sobre </v-toolbar-title>
        </v-btn>
        <span>&nbsp; &nbsp;</span>
        <v-divider inset vertical dark></v-divider>
        <v-spacer></v-spacer>
        <v-icon class="mr-2" @click="login()">{{ icons.mdiLogin }}</v-icon>
      </v-app-bar>
    </nav>
    <v-content v-if="loading === false" fluid class="mx-4">
      <template v-for="(item, i) in cursosInfo">
        <v-row style="padding-top: 15px" :key="i" align="center" justify="center">
          <v-hover v-slot:default="{ hover }">
            <v-card min-width="1500" max-width="1500" :elevation="hover ? 100 : 2" :class="{ 'on-hover': hover }">
              <div class="d-flex flex-no-wrap justify-space-between">
                <v-avatar tile size="150" class="ma-3">
                  <v-img style="border-radius: 25%" aspect-ratio=1 :src="item.fotografia"></v-img>
                </v-avatar>
                <div>
                  <v-card-title style="color:green;" class="mt-4 subheading text-left font-weight-bold">{{ item.nome }}</v-card-title>
                  <v-card-text>
                    <v-row dense justify="space-between">
                      <v-col cols="2">
                        <p class="mt-4 subheading text-left font-weight-light"> Abreviatura: {{ item.abreviatura }}</p>
                      </v-col>
                      <v-col cols="3">
                        <p class="mt-4 subheading text-left font-weight-light"> Tipo: {{ item.tipo }}</p>
                      </v-col>
                      <v-col>
                        <p class="mt-4 subheading text-left font-weight-light"> Semestres: {{ item.semestres }}</p>
                      </v-col>
                      <v-col>
                        <p class="mt-4 subheading text-left font-weight-light"> ECTS: {{ item.ECTS }}</p>
                      </v-col>
                      <v-col>
                        <p class="mt-4 subheading text-left font-weight-light"> Vagas: {{ item.vagas }}</p>
                      </v-col>
                    </v-row>
                    <v-row dense justify="space-between">
                      <p class="mt-4 subheading text-left  font-weight-light">{{ item.objetivos }}</p>
                    </v-row>
                    <v-row dense justify="space-between">
                      <p class="mt-4 subheading text-left font-weight-light"> Contacto: {{ item.contato }}</p>
                    </v-row>
                  </v-card-text>
                </div>
              </div>
            </v-card>
          </v-hover>
        </v-row>
      </template>
    </v-content>
    <v-content v-else fluid class="mx-4">
      <v-container style="widht:100%; height:100%; text-align:center" fluid>A carregar...</v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mdiLogin, mdiHome  } from '@mdi/js';

export default {
  data() {
    return {
      loading: true,
      cursosInfo: [],
      icons: { mdiLogin, mdiHome }
    };
  },
  mounted(){
    this.getCursos();
  },
  methods: {
    home() {
      this.$router.push("/");
    },
    login() {
      this.$router.push("/login");
    },
    atividades() {
      this.$router.push("/atividades");
    },
    sobre() {
      this.$router.push("/sobre");
    },
    getCursos() {
      this.loading = true;
      axios.get('api/cursosM').then(response => {
        this.cursosInfo = response.data;
        this.cursosInfo.forEach(curso => {
          curso.fotografia = `/storage/logoCursos/${curso.fotografia}`;
        });
      })
      .catch(response => {
        this.cursosInfo = [];
      })
      .finally(() => this.loading = false);
    },
  }
};
</script>