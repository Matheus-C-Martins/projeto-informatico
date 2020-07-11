<template>
  <v-card>
    <v-card-title> Editar Docente </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
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
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col>
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
                <v-text-field label="Data da Atividade"
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
              <v-date-picker v-model="docente.data" no-title @click:date="$refs.menuData.save(docente.data)">
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-menu
              ref="menuTime"
              v-model="menuTime"
              :close-on-content-click="false"
              :return-value.sync="docente.hora"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início da Atividade"
                  v-model="docente.hora"
                  outlined
                  dense
                  :error-messages="horaErrors"
                  @input="$v.docente.hora.$touch()"
                  @blur="$v.docente.hora.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="docente.hora" full-width @click:minute="$refs.menuTime.save(docente.hora)">
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
      <button class="btn btn-secondary" @click.stop.prevent="save"> Editar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  props: ["docente"],
  mixins: [validationMixin],
  validations: {
    docente: {
      sala: { required },
      data: { required },
      hora: { required },
    }
  },
  data: function() {
    return {
      salas: [],
      menuData: false,
      menuTime: false,
    };
  },
  computed: {
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
    horaErrors () {
      const errors = []
      if (!this.$v.docente.hora.$dirty) return errors;
      !this.$v.docente.hora.required && errors.push('É obrigatório escolher uma hora de início');
      return errors
    },
  },
  mounted(){
    this.getSalas();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getSalas() {
      axios.get(`api/salas`).then(response => {
        this.salas = response.data.data;
        this.salas.forEach(aux => {
          if(aux.nome === this.docente.sala){
            this.docente.sala = aux.id;
          }
        });
      })
      .catch(response => {
        this.salas = [];
      })
    },
    save() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      if (this.docente.hora.length < 8 && this.docente.hora.length === 5){
        this.docente.data = this.docente.data + " " + this.docente.hora + ":00";
      } else {
        this.docente.data = this.docente.data + " " + this.docente.hora;
      }
      
      this.$emit("save", this.docente);
    }
  }
};
</script>
