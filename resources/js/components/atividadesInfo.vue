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
        <v-btn text>
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
      <v-footer app inset absolute color="#FFFFFF">
        <v-row dense>
          <v-card tile flat width="100%">
            <v-card-text style="padding-top: 0px; padding-bottom: 0px;">
              <v-row align="end" justify="end">
                ©2020, feito por Matheus Martins e Ivan Silva
              </v-row>
            </v-card-text>
          </v-card>
        </v-row>
      </v-footer>
    </nav>
    <v-content v-if="loading === false" fluid class="mx-4">
      <template v-for="(item, i) in atividadesInfo">
        <v-row style="padding-top: 15px" :key="i" align="center" justify="center">
          <v-hover v-slot:default="{ hover }">
            <v-card min-width="1500" max-width="1500" :elevation="hover ? 100 : 2" :class="{ 'on-hover': hover }">
              <div class="d-flex flex-no-wrap justify-space-between">
                <v-avatar tile size="150" class="ma-3">
                  <v-img style="border-radius: 25%" aspect-ratio=1 src="/storage/Logo_DEI.png"></v-img>
                </v-avatar>
                <div>
                  <v-card-title style="color:green;" class="mt-4 subheading text-left font-weight-bold">{{ item.nome }}</v-card-title>
                  <v-card-text>
                    <v-row dense justify="space-between">
                      <p class="mt-4 subheading text-left  font-weight-light">{{ item.descricao }} <span v-if="item.nome === 'Workshop'"> Workshops disponíveis:
                        <template v-for="(w, j) in workshops"> {{w.nome}}<span :key="j" v-if="j < workshops.length-1">,</span>
                        <span :key="j" v-else>.</span></template></span> </p>
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
      atividadesInfo: [],
      workshops: [],
      icons: { mdiLogin, mdiHome }
    };
  },
  mounted() {
    this.getWorkshops();
    this.getAtividades();
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
    sobre() {
      this.$router.push("/sobre");
    },
    getAtividades() {
      this.loading = true;
      axios.get('api/atividadesM').then(response => {
        this.atividadesInfo = response.data;
      })
      .catch(response => {
        this.atividadesInfo = [];
      })
      .finally(() => this.loading = false);
    },
    getWorkshops() {
      axios.get(`api/workshops`).then(response => {
        this.workshops = response.data.data;
      })
      .catch(response => {
        this.workshops = [];
      })
    },
  }
};
</script>