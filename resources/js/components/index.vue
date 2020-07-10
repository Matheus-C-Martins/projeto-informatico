<template>
  <v-app id="inspire">
    <nav>
      <v-app-bar app dense dark color="green" flat hide-on-scroll>
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
        <v-btn @click="sobre()" text>
          <v-toolbar-title> Sobre </v-toolbar-title>
        </v-btn>
        <span>&nbsp; &nbsp;</span>
        <v-divider inset vertical dark></v-divider>
        <v-spacer></v-spacer>
        <v-icon class="mr-2" @click="login()">{{ icons.mdiLogin }}</v-icon>
      </v-app-bar>
    </nav>
    <v-content fluid class="mx-4">
      <v-row dense justify="center" align="center">
        <v-img max-width="65%" src="storage/logo_estg.jpg"></v-img>
      </v-row>
      <v-row dense justify="center" align="center">
        <v-col>
          <v-card @click="cursos()" color="green" dark min-width="155" class="mx-auto text-center">
            <v-card-text class="display-1"> Cursos </v-card-text>
          </v-card>
        </v-col>
        <v-col>
          <v-card @click="atividades()" color="green" dark min-width="155" class="mx-auto text-center">
            <v-card-text class="display-1"> Atividades </v-card-text>
          </v-card>
        </v-col>
        <v-col>
          <v-card @click="sobre()" color="green" dark min-width="155" class="mx-auto text-center">
            <v-card-text class="display-1"> Sobre </v-card-text>
          </v-card>
        </v-col>
      </v-row>
        <v-footer absolute padless color="#FFFFFF">
          <v-row dense>
            <v-card tile flat width="100%">
              <v-card-text>
                <v-row>
                  <v-spacer></v-spacer>
                  ©2020, feito por Matheus Martins e Ivan Silva
                  <span>&nbsp; &nbsp;</span>
                </v-row>
              </v-card-text>
            </v-card>
          </v-row>
        </v-footer>
    </v-content>
  </v-app>
</template>

<script>
import { mdiLogin } from '@mdi/js';

export default {
  data() {
    return {
      icons: { mdiLogin }
    };
  },
  methods: {
    login() {
      this.$router.push("/login");
    },
    cursos() {
      this.$router.push("/cursos");
    },
    atividades() {
      this.$router.push("/atividades");
    },
    sobre() {
      this.$router.push("/sobre");
    }
  }
};
</script>

<style>
.btn-primary {
  background-color: #ffffff;
  border-color: #ffffff;
  color: #000000;
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.open > .dropdown-toggle.btn-primary {
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-primary:not(:disabled):not(.disabled):active,
.btn-primary:not(:disabled):not(.disabled).active,
.show > .btn-primary.dropdown-toggle {
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-primary:focus,
.btn-primary.focus,
.btn-primary:not(:disabled):not(.disabled):active:focus,
.btn-primary:not(:disabled):not(.disabled).active:focus,
.show > .btn-primary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
}

.btn-secondary:hover,
.btn-secondary:focus,
.btn-secondary:active,
.open > .dropdown-toggle.btn-secondary {
  background-color: #000000;
}

.btn-secondary:not(:disabled):not(.disabled):active,
.btn-secondary:not(:disabled):not(.disabled).active,
.show > .btn-secondary.dropdown-toggle {
  background-color: #000000;
  border-color: #000000;
}
</style>
