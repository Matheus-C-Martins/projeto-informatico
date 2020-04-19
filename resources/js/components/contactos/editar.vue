<template>
  <v-card>
    <v-card-title> Editar Contacto </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
           <v-text-field label="Telefone"
              outlined 
              v-model="contacto.telefone" 
              dense 
              @keypress.enter.stop.prevent="save"
              :error-messages="telefoneErrors"
              @input="$v.contacto.telefone.$touch()"
              @blur="$v.contacto.telefone.$touch()"></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Email"
              outlined
              v-model="contacto.email"
              dense
              @keypress.enter.stop.prevent="save"
              :error-messages="emailErrors"
              @input="$v.contacto.email.$touch()"
              @blur="$v.contacto.email.$touch()"></v-text-field>
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
    contacto: {
      telefone: { required, maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      email: { required, email },
    }
  },
  props: [
    'contacto',
  ],
  data: function() {
    return {
    };
  },
  computed: {
    emailErrors () {
      const errors = []
      if (!this.$v.contacto.email.$dirty) return errors;
      !this.$v.contacto.email.email && errors.push('O email tem de estar num formato válido');
      !this.$v.contacto.email.required && errors.push("O email é obrigatório");
      return errors
    },
    telefoneErrors () {
      const errors = []
      if (!this.$v.contacto.telefone.$dirty) return errors;
      !this.$v.contacto.telefone.telefoneRegex && errors.push('O telefone só pode conter números');
      !this.$v.contacto.telefone.maxLength && errors.push('O telefone só pode ter 9 digitos');
      !this.$v.contacto.telefone.minLength && errors.push('O telefone só pode ter 9 digitos');
      !this.$v.contacto.telefone.required && errors.push("O telefone é obrigatório");
      return errors
    }
  },
  watch: {
    contacto: function(newVal, oldVal){
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
      this.$emit("save", this.contacto);
    },
  },
}
</script>
