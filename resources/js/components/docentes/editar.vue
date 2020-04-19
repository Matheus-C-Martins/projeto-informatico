<template>
  <v-card>
    <v-card-title> Editar Docente </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
           <v-text-field label="Telefone Interno"
              outlined 
              v-model="docente.telefone_interno" 
              dense 
              @keypress.enter.stop.prevent="save"
              :error-messages="telefone_internoErrors"
              @input="$v.docente.telefone_interno.$touch()"
              @blur="$v.docente.telefone_interno.$touch()"></v-text-field>
          </v-col>
          <v-col>
           <v-text-field label="Telemóvel"
              outlined 
              v-model="docente.telemovel" 
              dense 
              @keypress.enter.stop.prevent="save"
              :error-messages="telemovelErrors"
              @input="$v.docente.telemovel.$touch()"
              @blur="$v.docente.telemovel.$touch()"></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Email"
              outlined
              v-model="docente.email"
              dense
              @keypress.enter.stop.prevent="save"
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
      <button class="btn btn-secondary" @click.stop.prevent="save"> Editar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, maxLength, minLength, email, helpers } from "vuelidate/lib/validators";
export const telefoneRegex = helpers.regex('telefoneRegex', /^[0-9]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    docente: {
      telefone_interno: { required, maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      telemovel: { required, maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      email: { required, email },
    }
  },
  props: [
    'docente',
  ],
  data: function() {
    return {
    };
  },
  computed: {
    telefone_internoErrors () {
      const errors = []
      if (!this.$v.docente.telefone_interno.$dirty) return errors;
      !this.$v.docente.telefone_interno.telefoneRegex && errors.push('O telefone interno só pode conter números');
      !this.$v.docente.telefone_interno.maxLength && errors.push('O telefone interno só pode ter 9 digitos');
      !this.$v.docente.telefone_interno.minLength && errors.push('O telefone interno só pode ter 9 digitos');
      !this.$v.docente.telefone_interno.required && errors.push("O telefone interno é obrigatório");
      return errors
    },
    emailErrors () {
      const errors = []
      if (!this.$v.docente.email.$dirty) return errors;
      !this.$v.docente.email.email && errors.push('O email tem de estar num formato válido');
      !this.$v.docente.email.required && errors.push("O email é obrigatório");
      return errors
    },
    telemovelErrors () {
      const errors = []
      if (!this.$v.docente.telemovel.$dirty) return errors;
      !this.$v.docente.telemovel.telefoneRegex && errors.push('O telemóvel só pode conter números');
      !this.$v.docente.telemovel.maxLength && errors.push('O telemóvel só pode ter 9 digitos');
      !this.$v.docente.telemovel.minLength && errors.push('O telemóvel só pode ter 9 digitos');
      !this.$v.docente.telemovel.required && errors.push("O telemóvel é obrigatório");
      return errors
    }
  },
  watch: {
    docente: function(newVal, oldVal){
      newVal;
    }
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
      this.$emit("save", this.docente);
    },
  },
}
</script>
