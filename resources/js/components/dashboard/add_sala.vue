<template>
  <v-card>
    <v-card-title> Adicionar Sala </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Edifício"
              @keypress.enter.stop.prevent="create"
              v-model="sala.edificio"
              :error-messages="edificioErrors"
              outlined
              dense
              :items="edificios"
              item-value="edificio"
              @input="$v.sala.edificio.$touch()"
              @blur="$v.sala.edificio.$touch()">
            </v-select>
          </v-col>
          <v-col>
            <v-text-field label="Nome"
              @keypress.enter.stop.prevent="create"
              v-model="sala.nome"
              :error-messages="nomeErrors"
              outlined
              dense
              @input="$v.sala.nome.$touch()"
              @blur="$v.sala.nome.$touch()">
            </v-text-field>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary block" @click.stop.prevent="create"> Adicionar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, maxLength } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    sala: {
      edificio: { required },
      nome: { required, maxLength: maxLength(255) },
    }
  },
  data() {
    return {
      sala: {},
      edificios: [
        { edificio: "A", text: "Edifício A" },
        { edificio: "B", text: "Edifício B" },
        { edificio: "C", text: "Edifício C" },
        { edificio: "D", text: "Edifício D" },
        { edificio: "E", text: "Edifício E" },
        { edificio: "Biblioteca", text: "Biblioteca" }
      ]
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.sala.nome.$dirty) return errors;
      !this.$v.sala.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.sala.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    edificioErrors() {
      const errors = [];
      if (!this.$v.sala.edificio.$dirty) return errors;
      !this.$v.sala.edificio.required && errors.push("É obrigatório indicar um edifício");
      return errors;
    },
  },
  methods: {
    close() {
      this.$emit("close");
    },
    create() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("create", this.sala);
    }
  }
};
</script>
