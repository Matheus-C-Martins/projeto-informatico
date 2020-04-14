<template>
  <v-app id="inspire">
    <v-row dense no-gutters>
      <v-col md="8" lg="6" class="bg-color">
        <v-container fluid class="alinhamento d-flex align-items-center py-5">
          <v-row dense >
            <v-col md="9" lg="8" class="mx-auto">
              <v-row>
                <v-col class="pa-0">
                  <img class="responsive-image" src="/storage/Logo_IPLeiria.png"/>
                </v-col>
                <v-col class="pa-0">
                  <img class="responsive-image" height="130" width="130" src="/storage/Logo_DEI.png"/>
                </v-col>
              </v-row>
              <h3>DEI@Tour</h3>
              <h3 class="info-heading">Plataforma de Apoio ao DEI</h3>
              <br>
              <h6 class="info-heading mb-4">©2019-2020 POLITÉCNICO DE LEIRIA</h6>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
      <v-col md="8" lg="6">
        <v-container fluid class="alinhamento d-flex align-items-center py-5">
          <v-row dense align="center" justify="center">
            <v-col md="9" lg="8" class="mx-auto">
              <v-form>
                <v-text-field label="Endereço de Email"
                  v-model="user.email"
                  outlined
                  dense
                  @keypress.enter.stop.prevent="login">
                </v-text-field>
                <v-text-field label="Password"
                  v-model="user.password"
                  outlined
                  dense
                  @keypress.enter.stop.prevent="login"
                  :value="user.password"
                  :append-icon="value ? 'mdi-eye-off' : 'mdi-eye'"
                  @click:append="() => (value = !value)"
                  :type="value ? 'password' : 'text'">
                </v-text-field>
                <button @click.stop.prevent="login" class="btn btn-secondary block"> Login </button>
              </v-form>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
    </v-row>
  </v-app>
</template>

<script>
export default {
  data: function() {
    return {
      drawer: false,
      value: true,
      user: {
        email: null,
        password: null,
      },
    };
  },
  props: [],
  methods: {
    login: function() {
      axios.post("/api/login", this.user).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        localStorage.setItem('user-token', response.data.access_token) //Store The Token in Localstorage
        axios.defaults.headers.common.Authorization ="Bearer " + response.data.access_token;

        axios.get("api/user").then(response => {
          if(response.status!=200) {
            console.log("1- "+response.data)
            Vue.toasted.error("Algo Correu mal... "+response.data);
            return;
          }
          this.user = response.data;
          this.$router.push("/backoffice/home").catch(err => err);
          Vue.toasted.show("Bem-vindo ao DEI@Tour");
        })
        .catch(response => {
          Vue.toasted.error("Algo Correu mal...");
        });
      })
      .catch(response => {
        Vue.toasted.error("Algo Correu mal...");
      });
    },
  },
};
</script>

<style scoped>
h3, h6 {
  color: #ffffff;
  text-align: center;
}

.alinhamento{
  min-height: 100vh;
}

.bg-color {
  background-color: #000000;
  background-size: cover;
  background-position: center;
}

.info-heading {
  font-weight: 300;
}

.responsive-image {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 380px;
  height: auto;
}

.block {
  display: block;
  width: 100%;
  cursor: pointer;
  text-align: center;
}
</style>

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
