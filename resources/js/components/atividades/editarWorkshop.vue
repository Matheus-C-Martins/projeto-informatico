<template>
  <v-card>
    <v-card-title> Editar Workshop </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Workshop"
              outlined
              :items="workshops"
              item-value="id"
              item-text="nome"
              v-model="workshop.workshop"
              :error-messages="workshopErrors"
              @input="$v.workshop.workshop.$touch()"
              @blur="$v.workshop.workshop.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col>
          <v-col>
            <v-menu
              ref="menuData"
              v-model="menuData"
              :close-on-content-click="false"
              :return-value.sync="workshop.data"
              transition="scale-transition"
              offset-y
              min-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Data do Workshop"
                  v-model="workshop.data"
                  outlined
                  dense
                  :error-messages="dataErrors"
                  @input="$v.workshop.data.$touch()"
                  @blur="$v.workshop.data.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="workshop.data" no-title>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menuData = false"> Cancelar </v-btn>
                <v-btn text color="primary" @click="$refs.menuData.save(workshop.data)"> Ok </v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-menu
              ref="menuTime"
              v-model="menuTime"
              :close-on-content-click="false"
              :return-value.sync="workshop.hora"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início do Workshop"
                  v-model="workshop.hora"
                  outlined
                  dense
                  :error-messages="horaErrors"
                  @input="$v.workshop.hora.$touch()"
                  @blur="$v.workshop.hora.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="workshop.hora" full-width @click:minute="$refs.menuTime.save(workshop.hora)">
              </v-time-picker>
            </v-menu>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-textarea label="Descrição"
              clearable
              outlined
              v-model="workshop.descricao"
              dense
              hide-details
            ></v-textarea>
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
  props: ["workshop"],
  mixins: [validationMixin],
  validations: {
    workshop: {
      workshop: { required },
      data: { required },
      hora: { required },
    }
  },
  data: function() {
    return {
      workshops: [],
      menuData: false,
      menuTime: false,
    };
  },
  computed: {
    workshopErrors() {
      const errors = [];
      if (!this.$v.workshop.workshop.$dirty) return errors;
      !this.$v.workshop.workshop.required && errors.push("É obrigatório escolher um workshop");
      return errors;
    },
    dataErrors() {
      const errors = [];
      if (!this.$v.workshop.data.$dirty) return errors;
      !this.$v.workshop.data.required && errors.push("É obrigatório escolher uma data");
      return errors;
    },
    horaErrors () {
      const errors = []
      if (!this.$v.workshop.hora.$dirty) return errors;
      !this.$v.workshop.hora.required && errors.push('É obrigatório escolher uma hora de início');
      return errors
    },
  },
  mounted(){
    this.getWorkshops();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getWorkshops() {
      axios.get(`api/workshops`).then(response => {
        this.workshops = response.data.data;
        this.workshops.forEach(aux => {
          if(aux.nome === this.workshop.workshop){
            this.workshop.workshop = aux.id;
          }
        });
      })
      .catch(response => {
        this.workshops = [];
      })
    },
    save() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      if (this.workshop.hora.length < 8 && this.workshop.hora.length === 5){
        this.workshop.data = this.workshop.data + " " + this.workshop.hora + ":00";
      } else {
        this.workshop.data = this.workshop.data + " " + this.workshop.hora;
      }
      
      this.$emit("save", this.workshop);
    }
  }
};
</script>
