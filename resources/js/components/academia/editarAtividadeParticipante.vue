<template>
  <v-card>
    <v-card-title> Editar Participante </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-menu
              ref="menuData"
              v-model="menuData"
              :close-on-content-click="false"
              :return-value.sync="participante.data"
              transition="scale-transition"
              offset-y
              min-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Data da Atividade"
                  v-model="participante.data"
                  outlined
                  dense
                  :error-messages="dataErrors"
                  @input="$v.participante.data.$touch()"
                  @blur="$v.participante.data.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="participante.data" no-title>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menuData = false"> Cancelar </v-btn>
                <v-btn text color="primary" @click="$refs.menuData.save(participante.data)"> Ok </v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-menu
              ref="menuTime"
              v-model="menuTime"
              :close-on-content-click="false"
              :return-value.sync="participante.hora"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início da Atividade"
                  v-model="participante.hora"
                  outlined
                  dense
                  :error-messages="horaErrors"
                  @input="$v.participante.hora.$touch()"
                  @blur="$v.participante.hora.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="participante.hora" full-width @click:minute="$refs.menuTime.save(participante.hora)">
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
  props: ["participante"],
  mixins: [validationMixin],
  validations: {
    participante: {
      data: { required },
      hora: { required },
    }
  },
  data: function() {
    return {
      menuData: false,
      menuTime: false,
    };
  },
  computed: {
    dataErrors() {
      const errors = [];
      if (!this.$v.participante.data.$dirty) return errors;
      !this.$v.participante.data.required && errors.push("É obrigatório escolher uma data");
      return errors;
    },
    horaErrors () {
      const errors = []
      if (!this.$v.participante.hora.$dirty) return errors;
      !this.$v.participante.hora.required && errors.push('É obrigatório escolher uma hora de início');
      return errors
    },
  },
  methods: {
    close() {
      this.$emit("close");
    },
    save() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      if (this.participante.hora.length < 8 && this.participante.hora.length === 5){
        this.participante.data = this.participante.data + " " + this.participante.hora + ":00";
      } else {
        this.participante.data = this.participante.data + " " + this.participante.hora;
      }
      
      this.$emit("save", this.participante);
    }
  }
};
</script>
