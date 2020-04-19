<template>
  <v-card>
    <v-card-title> Criar Contacto </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="contacto.nome"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="nomeErrors"
              @input="$v.contacto.nome.$touch()"
              @blur="$v.contacto.nome.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Telefone"
              outlined 
              v-model="contacto.telefone" 
              dense 
              @keypress.enter.stop.prevent="create"
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
              @keypress.enter.stop.prevent="create"
              :error-messages="emailErrors"
              @input="$v.contacto.email.$touch()"
              @blur="$v.contacto.email.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-select label="Sexo"
              outlined
              :items="sexos"
              item-value="sexo"
              v-model="contacto.sexo"
              :error-messages="sexoErrors"
              @input="$v.contacto.sexo.$touch()"
              @blur="$v.contacto.sexo.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
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
    contacto: {
      nome: { required, alphaDiacritic, maxLength: maxLength(255) },
      telefone: { maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      email: { email },
      sexo: { required }
    }
  },
  props: ["contacto"],
  data: function() {
    return {
      email: 1,
      sexos: [
        { sexo: "masculino", text: "Masculino" },
        { sexo: "feminino", text: "Feminino" },
        { sexo: "outro", text: "Outro" },
      ],
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.contacto.nome.$dirty) return errors;
      !this.$v.contacto.nome.alphaDiacritic && errors.push("O nome só pode conter espaços ou letras");
      !this.$v.contacto.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.contacto.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    emailErrors () {
      const errors = []
      if (!this.$v.contacto.email.$dirty) return errors;
      !this.$v.contacto.email.email && errors.push('O email tem de estar num formato válido');
      return errors
    },
    telefoneErrors () {
      const errors = []
      if (!this.$v.contacto.telefone.$dirty) return errors;
      !this.$v.contacto.telefone.telefoneRegex && errors.push('O telefone só pode conter números');
      !this.$v.contacto.telefone.maxLength && errors.push('O telefone só pode ter 9 digitos');
      !this.$v.contacto.telefone.minLength && errors.push('O telefone só pode ter 9 digitos');
      return errors
    },
    sexoErrors () {
      const errors = []
      if (!this.$v.contacto.sexo.$dirty) return errors;
      !this.$v.contacto.sexo.required && errors.push('O sexo é obrigatório');
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
      this.$emit("create", this.contacto);
    }
  }
};
</script>
