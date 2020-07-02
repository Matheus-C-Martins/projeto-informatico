<template>
  <v-card>
    <v-card-title> Associar Participante </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Participante"
              outlined
              :items="participantes"
              item-value="id"
              item-text="nome"
              v-model="participante.participante"
              :error-messages="participanteErrors"
              @input="$v.participante.participante.$touch()"
              @blur="$v.participante.participante.$touch()"
              dense
              @keypress.enter.stop.prevent="associar"
            ></v-select>
          </v-col>
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
                <v-text-field label="Data da atividade"
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
              :return-value.sync="participante.time"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início da atividade"
                  v-model="participante.time"
                  outlined
                  dense
                  :error-messages="timeErrors"
                  @input="$v.participante.time.$touch()"
                  @blur="$v.participante.time.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="participante.time" full-width @click:minute="$refs.menuTime.save(participante.time)">
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
    participante: {
      participante: { required },
      data: { required },
      time: { required },
    }
  },
  data() {
    return {
      participantes: [],
      participante: {},
      menuData: false,
      menuTime: false,
    };
  },
  computed: {
    participanteErrors() {
      const errors = [];
      if (!this.$v.participante.participante.$dirty) return errors;
      !this.$v.participante.participante.required && errors.push("É obrigatório escolher um participante");
      return errors;
    },
    dataErrors() {
      const errors = [];
      if (!this.$v.participante.data.$dirty) return errors;
      !this.$v.participante.data.required && errors.push("É obrigatório escolher uma data");
      return errors;
    },
    timeErrors () {
      const errors = []
      if (!this.$v.participante.time.$dirty) return errors;
      !this.$v.participante.time.required && errors.push('É obrigatório escolher uma hora de início');
      return errors
    },
  },
  mounted(){
    this.getParticipantes();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getParticipantes() {
      axios.get(`api/academias/participantes`).then(response => {
        this.participantes = response.data.data;
      })
      .catch(response => {
        this.participantes = [];
      })
    },
    associar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.participante.data = this.participante.data + " " + this.participante.time + ":00";
      this.$emit("associar", this.participante);
    }
  }
};
</script>
