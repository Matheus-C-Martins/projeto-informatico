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
    </nav>
    <v-content v-if="loading === false" fluid class="mx-4">
      
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
      icons: { mdiLogin, mdiHome }
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
    sobre() {
      this.$router.push("/sobre");
    }
  }
};
</script>