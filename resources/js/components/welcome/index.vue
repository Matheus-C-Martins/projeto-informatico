<template>
  <v-app id="inspire">
    <v-row no-gutters>
      <v-col md="8" lg="6" class="bg-color">
        <v-container fluid class="alinhamento d-flex align-items-center py-5">
          <v-row>
            <v-col md="9" lg="8" class="mx-auto">
              <img class="responsive" src="/storage/Logo_IPLeiria.png"/>
              <br>
              <h3>Plataforma de Apoio ao DEI</h3>
              <h6 class="info-heading mb-4">©2019-2020 POLITÉCNICO DE LEIRIA</h6>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
      <v-col md="8" lg="6">
        <v-container fluid class="alinhamento d-flex align-items-center py-5">
          <v-row>
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
      }
    };
  },
  props: [],
  methods: {
    login: function() {
      axios.post("/api/login", this.user).then(response => {
        if(response.status!=200){
          Vue.toasted.error("Something went wrong... "+response.data);
          return;
        }
        localStorage.setItem('user-token', response.data.access_token) //Store The Token in Localstorage
        axios.defaults.headers.common.Authorization ="Bearer " + response.data.access_token;

        axios.get("api/user").then(response => {
          if(response.status!=200) {
            Vue.toasted.error("Something went wrong... "+response.data);
            return;
          }
          this.user = response.data;
          //this.$router.push("/home/quick").catch(err => err);
          Vue.toasted.success("Bem-vindo ao DEI@Tour!");
        })
        .catch(response => {
          Vue.toasted.error("Something went wrong...");
        });
      })
      .catch(response => {
        Vue.toasted.error("Something went wrong...");
      });
    },
  },
};
</script>

<style scoped>
:root {
  --input-padding-x: 1.5rem;
  --input-padding-y: 0.75rem;
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

.form-label-group {
  position: relative;
  margin-bottom: 1rem;
}

.form-label-group > input,
.form-label-group > label {
  padding: var(--input-padding-y) var(--input-padding-x);
  height: auto;
  border-radius: 2rem;
}

.form-label-group > label {
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  width: 100%;
  margin-bottom: 0;
  /* Override default `<label>` margin */
  line-height: 1.5;
  color: #495057;
  cursor: text;
  /* Match the input under the label */
  border: 1px solid transparent;
  border-radius: 0.25rem;
  transition: all 0.1s ease-in-out;
}

.form-label-group input::-webkit-input-placeholder {
  color: transparent;
}

.form-label-group input:-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-moz-placeholder {
  color: transparent;
}

.form-label-group input::placeholder {
  color: transparent;
}

.form-label-group input:not(:placeholder-shown) {
  padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
  padding-bottom: calc(var(--input-padding-y) / 3);
}

.form-label-group input:not(:placeholder-shown) ~ label {
  padding-top: calc(var(--input-padding-y) / 3);
  padding-bottom: calc(var(--input-padding-y) / 3);
  font-size: 12px;
  color: #777;
}

.responsive {
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

h3, h6 {
  color: white;
  text-align: center;
}
</style>

<style>
/* LOGIN Button - GLOBAL SCOPE */

.btn-secondary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  background: #000000;
}
.btn-light:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  background: #20b2aa;
}

/** BLUETY meaning lightseagreen */
.bluety{
  color: #20b2aa;
}

/* POP UP */
.fader  {
    animation: fade 5s linear;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;

}

/* Standard syntax */
@keyframes infinite-rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes fade {
  0% { opacity: 0 }
  40% { opacity: 1 }
  75% { opacity: 1 }
  100% { opacity: 0 }
}
</style>
