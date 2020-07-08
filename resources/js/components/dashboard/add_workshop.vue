<template>
  <v-card>
    <v-card-title> Adicionar Workshop </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              @keypress.enter.stop.prevent="create"
              v-model="workshop.nome"
              :error-messages="nomeErrors"
              outlined
              dense
              @input="$v.workshop.nome.$touch()"
              @blur="$v.workshop.nome.$touch()">
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
    workshop: {
      nome: { required, maxLength: maxLength(255) },
    }
  },
  data() {
    return {
      workshop: {},
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.workshop.nome.$dirty) return errors;
      !this.$v.workshop.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.workshop.nome.required && errors.push("O nome é obrigatório");
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
      this.$emit("create", this.workshop);
    }
  }
};
</script>
