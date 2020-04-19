<template>
  <v-card>
    <v-card-title> Criar Docente </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="docente.nome"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="nomeErrors"
              @input="$v.docente.nome.$touch()"
              @blur="$v.docente.nome.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Telefone Interno"
              outlined 
              v-model="docente.telefone_interno" 
              dense 
              @keypress.enter.stop.prevent="create"
              :error-messages="telefone_internoErrors"
              @input="$v.docente.telefone_interno.$touch()"
              @blur="$v.docente.telefone_interno.$touch()"></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Telemóvel"
              outlined 
              v-model="docente.telemovel" 
              dense 
              @keypress.enter.stop.prevent="create"
              :error-messages="telemovelErrors"
              @input="$v.docente.telemovel.$touch()"
              @blur="$v.docente.telemovel.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Email"
              outlined
              v-model="docente.email"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="emailErrors"
              @input="$v.docente.email.$touch()"
              @blur="$v.docente.email.$touch()"></v-text-field>
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
import { required, maxLength, minLength, email, helpers } from "vuelidate/lib/validators";
export const alphaDiacritic = helpers.regex('alphaDiacritic', /^[a-zA-ZÀ-ž\s]*$/);
export const telefoneRegex = helpers.regex('telefoneRegex', /^[0-9]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    docente: {
      nome: { required, alphaDiacritic, maxLength: maxLength(255) },
      telemovel: { maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      email: { email },
      telefone_interno: { maxLength: maxLength(9), minLength: minLength(9), telefoneRegex }
    }
  },
  props: ["docente"],
  data: function() {
    return {
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.docente.nome.$dirty) return errors;
      !this.$v.docente.nome.alphaDiacritic && errors.push("O nome só pode conter espaços ou letras");
      !this.$v.docente.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.docente.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    emailErrors () {
      const errors = []
      if (!this.$v.docente.email.$dirty) return errors;
      !this.$v.docente.email.email && errors.push('O email tem de estar num formato válido');
      return errors
    },
    telemovelErrors () {
      const errors = []
      if (!this.$v.docente.telemovel.$dirty) return errors;
      !this.$v.docente.telemovel.telefoneRegex && errors.push('O telemóvel só pode conter números');
      !this.$v.docente.telemovel.maxLength && errors.push('O telemóvel só pode ter 9 digitos');
      !this.$v.docente.telemovel.minLength && errors.push('O telemóvel só pode ter 9 digitos');
      return errors
    },
    telefone_internoErrors () {
       const errors = []
      if (!this.$v.docente.telefone_interno.$dirty) return errors;
      !this.$v.docente.telefone_interno.telefoneRegex && errors.push('O telefone interno só pode conter números');
      !this.$v.docente.telefone_interno.maxLength && errors.push('O telefone interno só pode ter 9 digitos');
      !this.$v.docente.telefone_interno.minLength && errors.push('O telefone interno só pode ter 9 digitos');
      return errors
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
      this.$emit("create", this.docente);
    }
  }
};
</script>
