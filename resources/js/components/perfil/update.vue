<template>
  <v-card>
    <v-card-title> Editar Perfil </v-card-title>
    <v-card-text style="padding-bottom: 0px">
      <v-form>
        <v-text-field label="Nome"
          v-model="updatedUser.nome"
          :error-messages="nomeErrors"
          outlined
          dense
          @input="$v.updatedUser.nome.$touch()"
          @blur="$v.updatedUser.nome.$touch()"
          @keypress.enter.stop.prevent="updateProfile"
        ></v-text-field>
        <v-text-field label="Password Nova"
          v-model="updatedUser.new_password"
          :value="updatedUser.new_password"
          outlined
          dense
          :type="value ? 'password' : 'text'"
          :error-messages="newPasswordErrors"
          :append-icon="value ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append="() => (value = !value)"
          @input="$v.updatedUser.new_password.$touch()"
          @blur="$v.updatedUser.new_password.$touch()"
          @keypress.enter.stop.prevent="updateProfile"
        ></v-text-field>
        <v-text-field label="Confirmação da Nova Password"
          v-model="updatedUser.new_password_confirmation"
          :value="updatedUser.new_password_confirmation"
          outlined
          dense
          :type="value ? 'password' : 'text'"
          :error-messages="newPasswordConfirmationErrors"
          :append-icon="value ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append="() => (value = !value)"
          @input="$v.updatedUser.new_password_confirmation.$touch()"
          @blur="$v.updatedUser.new_password_confirmation.$touch()"
          @keypress.enter.stop.prevent="updateProfile"
        ></v-text-field>
        <v-text-field label="Password Atual"
          v-if="updatedUser.new_password"
          v-model="updatedUser.current_password"
          :value="updatedUser.current_password"
          outlined
          dense
          :type="value ? 'password' : 'text'"
          :error-messages="currentPasswordErrors"
          :append-icon="value ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append="() => (value = !value)"
          @input="$v.updatedUser.current_password.$touch()"
          @blur="$v.updatedUser.current_password.$touch()"
          @keypress.enter.stop.prevent="updateProfile">
        </v-text-field>
        <v-text-field label="Fotografia"
          v-model="imageName"
          @click="pickPhoto"
          readonly
          append-icon="mdi-camera"
          outlined
          dense>
        </v-text-field>
        <input type="file" style="display: none" ref="image" accept="image/*" @change="photoChanged"/>
      </v-form>
    </v-card-text>
    <v-card-actions style="padding-top: 0px">
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="cancelProfile"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="updateProfile"> Editar </button>
    </v-card-actions>
  </v-card>
</template>


<script>
import { validationMixin } from "vuelidate";
import { required, maxLength, minLength, sameAs, requiredIf, helpers } from "vuelidate/lib/validators";
export const alphaDiacritic = helpers.regex('alphaDiacritic', /^[a-zA-ZÀ-ž\s]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    updatedUser: {
      nome: { required, maxLength: maxLength(255), alphaDiacritic },
      current_password: { required: requiredIf(function(updatedUser){ return updatedUser.new_password }), minLength: minLength(3) },
      new_password: { minLength: minLength(3) },
      new_password_confirmation: { sameAsPassword: sameAs("new_password") },
    }
  },
  props: ["uType", "updatedUser"],
  data: function() {
    return {
      imageName: "",
      imageFile: "",
      valid: true,
      value: true
    };
  },
  methods: {
    cancelProfile() {
      this.$emit("cancel-update");
    },
    updateProfile() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      if(this.imageName === null) {
        this.updatedUser.fotografia = "";
      }
      this.$emit("update-profile", this.updatedUser);
    },
    pickPhoto() {
      this.$refs.image.click();
    },
    photoChanged(e) {
      const files = e.target.files;

      if (files[0] !== undefined) {
        this.imageName = files[0].name;
        if (this.imageName.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          this.updatedUser.fotografia = fr.result;
          this.imageFile = files[0];
        });
      } else {
        this.imageName = "";
        this.imageFile = "";
        this.updatedUser.fotografia = "";
      }
    }
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.updatedUser.nome.$dirty) return errors;
      !this.$v.updatedUser.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.updatedUser.nome.alphaDiacritic && errors.push("O nome só pode conter espaços ou letras");
      !this.$v.updatedUser.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    currentPasswordErrors() {
      const errors = [];
      if (!this.$v.updatedUser.current_password.$dirty) return errors;
      !this.$v.updatedUser.current_password.minLength && errors.push("A password atual tem de ter no mínimo 3 carateres");
      !this.$v.updatedUser.current_password.required && errors.push("A password atual é obrigatória");
      return errors;
    },
    newPasswordErrors() {
      const errors = [];
      if (!this.$v.updatedUser.new_password.$dirty) return errors;
      !this.$v.updatedUser.new_password.minLength &&  errors.push("A nova password tem de ter no mínimo 3 carateres");
      return errors;
    },
    newPasswordConfirmationErrors() {
      const errors = [];
      if (!this.$v.updatedUser.new_password_confirmation.$dirty) return errors;
      !this.$v.updatedUser.new_password_confirmation.sameAsPassword && errors.push("A confirmação de password e a nova password têm de ser iguais");
      return errors;
    }
  }
};
</script>
