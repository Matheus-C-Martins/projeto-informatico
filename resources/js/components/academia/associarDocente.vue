<template>
  <v-card>
    <v-card-title> Associar Docente </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Docente"
              outlined
              :items="docentes"
              item-value="id"
              item-text="nome"
              v-model="docente.docente"
              :error-messages="docenteErrors"
              @input="$v.docente.docente.$touch()"
              @blur="$v.docente.docente.$touch()"
              dense
              @keypress.enter.stop.prevent="associar"
            ></v-select>
          </v-col>
          <v-col>
            <v-select label="Sala"
              outlined
              :items="salas"
              item-value="id"
              item-text="nome"
              v-model="docente.sala"
              :error-messages="salaErrors"
              @input="$v.docente.sala.$touch()"
              @blur="$v.docente.sala.$touch()"
              dense
              @keypress.enter.stop.prevent="associar"
            ></v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-menu
              ref="menuData"
              v-model="menuData"
              :close-on-content-click="false"
              :return-value.sync="docente.data"
              transition="scale-transition"
              offset-y
              min-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Data da atividade"
                  v-model="docente.data"
                  outlined
                  dense
                  :error-messages="dataErrors"
                  @input="$v.docente.data.$touch()"
                  @blur="$v.docente.data.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="docente.data" no-title>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menuData = false"> Cancelar </v-btn>
                <v-btn text color="primary" @click="$refs.menuData.save(docente.data)"> Ok </v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-menu
              ref="menuTime"
              v-model="menuTime"
              :close-on-content-click="false"
              :return-value.sync="docente.time"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início da atividade"
                  v-model="docente.time"
                  outlined
                  dense
                  :error-messages="timeErrors"
                  @input="$v.docente.time.$touch()"
                  @blur="$v.docente.time.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="docente.time" full-width @click:minute="$refs.menuTime.save(docente.time)">
              </v-time-picker>
            </v-menu>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="associar"> Associar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    docente: {
      docente: { required },
      sala: { required },
      data: { required },
      time: { required },
    }
  },
  data: function() {
    return {
      docentes: [],
      salas: [],
      docente: {},
      menuData: false,
      menuTime: false,
    };
  },
  computed: {
    docenteErrors() {
      const errors = [];
      if (!this.$v.docente.docente.$dirty) return errors;
      !this.$v.docente.docente.required && errors.push("É obrigatório escolher um docente");
      return errors;
    },
    salaErrors() {
      const errors = [];
      if (!this.$v.docente.sala.$dirty) return errors;
      !this.$v.docente.sala.required && errors.push("É obrigatório escolher uma sala");
      return errors;
    },
    dataErrors() {
      const errors = [];
      if (!this.$v.docente.data.$dirty) return errors;
      !this.$v.docente.data.required && errors.push("É obrigatório escolher uma data");
      return errors;
    },
    timeErrors () {
      const errors = []
      if (!this.$v.docente.time.$dirty) return errors;
      !this.$v.docente.time.required && errors.push('É obrigatório escolher uma hora de início');
      return errors
    },
  },
  mounted(){
    this.getDocentes();
    this.getSalas();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getDocentes() {
      axios.get(`api/docentes`).then(response => {
        this.docentes = response.data.data;
      })
      .catch(response => {
        this.docentes = [];
      })
    },
    getSalas() {
      axios.get(`api/salas`).then(response => {
        this.salas = response.data.data;
      })
      .catch(response => {
        this.salas = [];
      })
    },
    associar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.docente.data = this.docente.data + " " + this.docente.time + ":00";
      this.$emit("associar", this.docente);
    }
  }
};
</script>
