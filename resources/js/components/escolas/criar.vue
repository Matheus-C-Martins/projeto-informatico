<template>
  <v-card>
    <v-card-title> Criar Escola </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="escola.nome"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="nomeErrors"
              @input="$v.escola.nome.$touch()"
              @blur="$v.escola.nome.$touch()"></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Localização"
              outlined
              v-model="escola.localizacao" 
              dense
              clearable
              @keypress.enter.stop.prevent="create"
              :error-messages="localizacaoErrors"
              @input="$v.escola.localizacao.$touch()"
              @blur="$v.escola.localizacao.$touch()"></v-text-field>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="create"> Criar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, maxLength, helpers } from "vuelidate/lib/validators";
export const alphaDiacritic = helpers.regex('alphaDiacritic', /^[a-zA-ZÀ-ž\s]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    escola: {
      nome: { required, alphaDiacritic, maxLength: maxLength(255) },
      localizacao: { required }
    }
  },
  props: ["escola"],
  data: function() {
    return {
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.escola.nome.$dirty) return errors;
      !this.$v.escola.nome.alphaDiacritic && errors.push("O nome só pode conter espaços ou letras");
      !this.$v.escola.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.escola.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    localizacaoErrors () {
      const errors = []
      if (!this.$v.escola.localizacao.$dirty) return errors;
      !this.$v.escola.localizacao.required && errors.push('A localização é obrigatória');
      return errors
    }
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
      this.$emit("create", this.escola);
    }
  }
};
</script>
