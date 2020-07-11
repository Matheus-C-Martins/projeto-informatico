<template>
  <v-card>
    <v-card-title> Associar Workshop </v-card-title>
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
              @keypress.enter.stop.prevent="associar"
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
              <v-date-picker v-model="workshop.data" no-title @click:date="$refs.menuData.save(workshop.data)">
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-menu
              ref="menuTime"
              v-model="menuTime"
              :close-on-content-click="false"
              :return-value.sync="workshop.time"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início do Workshop"
                  v-model="workshop.time"
                  outlined
                  dense
                  :error-messages="timeErrors"
                  @input="$v.workshop.time.$touch()"
                  @blur="$v.workshop.time.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="workshop.time" full-width @click:minute="$refs.menuTime.save(workshop.time)">
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
    workshop: {
      workshop: { required },
      data: { required },
      time: { required },
    }
  },
  data: function() {
    return {
      workshops: [],
      workshop: {},
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
    timeErrors () {
      const errors = []
      if (!this.$v.workshop.time.$dirty) return errors;
      !this.$v.workshop.time.required && errors.push('É obrigatório escolher uma hora de início');
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
      })
      .catch(response => {
        this.workshops = [];
      })
    },
    associar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.workshop.data = this.workshop.data + " " + this.workshop.time + ":00";
      this.$emit("associar", this.workshop);
    }
  }
};
</script>
