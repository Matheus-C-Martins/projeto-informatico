<template>
  <v-card>
    <v-card-title> Criar Novo Utilizador </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              @keypress.enter.stop.prevent="create"
              v-model="newUser.nome"
              :error-messages="nomeErrors"
              outlined
              dense
              @input="$v.newUser.nome.$touch()"
              @blur="$v.newUser.nome.$touch()">
            </v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Photo"
              v-model="imageName"
              @click="pickPhoto"
              readonly
              append-icon="mdi-camera"
              outlined
              dense>
            </v-text-field>
            <input
              type="file"
              style="display: none"
              ref="image"
              accept="image/*"
              @change="photoChanged">
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Endereço de Email"
              @keypress.enter.stop.prevent="create"
              v-model="newUser.email"
              :error-messages="emailErrors"
              outlined
              dense
              @change="checkEmailExists(); $v.newUser.email.$touch()"
              @input="$v.newUser.email.$touch()"
              @blur="$v.newUser.email.$touch()">
            </v-text-field>
          </v-col>
          <v-col>
            <v-select label="Tipo de Utilizador"
              @keypress.enter.stop.prevent="create"
              v-model="newUser.tipo"
              :error-messages="userTypeErrors"
              outlined
              dense
              :items="user_type"
              item-value="user_type"
              @input="$v.newUser.tipo.$touch()"
              @blur="$v.newUser.tipo.$touch()">
            </v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Password"
              @keypress.enter.stop.prevent="create"
              v-model="newUser.password"
              :value="newUser.password"
              :append-icon="value ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append="() => (value = !value)"
              :type="value ? 'password' : 'text'"
              :error-messages="passwordErrors"
              outlined
              dense
              @input="$v.newUser.password.$touch()"
              @blur="$v.newUser.password.$touch()">
            </v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Password Confirmation"
              @keypress.enter.stop.prevent="create"
              v-model="newUser.password_confirmation"
              :value="newUser.password_confirmation"
              :append-icon="value ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append="() => (value = !value)"
              :type="value ? 'password' : 'text'"
              :error-messages="passwordConfirmationErrors"
              outlined
              dense
              @input="$v.newUser.password_confirmation.$touch()"
              @blur="$v.newUser.password_confirmation.$touch()">
            </v-text-field>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary block" @click.stop.prevent="create"> Criar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, maxLength, minLength, numeric, email, sameAs, helpers } from "vuelidate/lib/validators";
export const alphaDiacritic = helpers.regex('alphaDiacritic', /^[a-zA-ZÀ-ž\s]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    newUser: {
      nome: { required, maxLength: maxLength(255), alphaDiacritic },
      email: { required, email },
      tipo: { required },
      password: { required, minLength: minLength(3) },
      password_confirmation: { required, sameAsPassword: sameAs("password") }
    }
  },
  props: [ "newUser" ],
  data: function() {
    return {
      imageName: '',
      imageFile: '',
      valid: true,
      value: true,
      choosenUser: 0,
      email: 0,
      user_type: [
        { user_type: "a", text: "Administrador" },
        { user_type: "g", text: "Gestor" }
      ]
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.newUser.nome.$dirty) return errors;
      !this.$v.newUser.nome.alphaDiacritic && errors.push("O nome só pode conter espaços ou letras");
      !this.$v.newUser.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.newUser.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    emailErrors () {
      const errors = []
      if (!this.$v.newUser.email.$dirty) return errors;
      !(this.email == 0) && errors.push("O email já está a ser utilizado");
      !this.$v.newUser.email.email && errors.push('O email tem de estar num formato válido')
      !this.$v.newUser.email.required && errors.push('O email é obrigatório')
      return errors
    },
    userTypeErrors() {
      const errors = [];
      if (!this.$v.newUser.tipo.$dirty) return errors;
      !this.$v.newUser.tipo.required && errors.push("O tipo de utilizador é obrigatório");
      return errors;
    },
    passwordErrors () {
      const errors = []
      if (!this.$v.newUser.password.$dirty) return errors
      !this.$v.newUser.password.minLength && errors.push('A password tem de ter no mínimo 3 carateres')
      !this.$v.newUser.password.required && errors.push('A password é obrigatória')
      return errors
    },
    passwordConfirmationErrors () {
      const errors = []
      if (!this.$v.newUser.password_confirmation.$dirty) return errors
      !this.$v.newUser.password_confirmation.sameAsPassword && errors.push('A confirmação de password e a nova password têm de ser iguais')
      !this.$v.newUser.password_confirmation.required && errors.push('A confirmação de password é obrigatória')
      return errors
    },
  },
  methods: {
    close() {
      this.$emit("close");
    },
    create() {
      this.$v.$touch();
      if(this.$v.$error || this.email == 1) {
        return;
      }
      if(this.imageName === null) {
        this.newUser.fotografia = "";
      }
      this.$emit("create", this.newUser);
    },
    pickPhoto () {
      this.$refs.image.click ();
    },
    photoChanged (e) {
      const files = e.target.files
      if(files[0] !== undefined) {
        this.imageName = files[0].name
        if(this.imageName.lastIndexOf('.') <= 0) {
          return;
        }
        const fr = new FileReader ()
        fr.readAsDataURL(files[0])
        fr.addEventListener('load', () => {
          this.newUser.fotografia = fr.result
          this.imageFile = files[0]
        })
      } else {
        this.imageName = '';
        this.imageFile = '';
        this.newUser.fotografia = '';
      }
    },
    checkEmailExists(){
      this.email = 1;
      if(this.newUser === undefined){
        return;
      }
      axios.get(`api/users/${this.newUser.email}`).then(response => {
        if(response.status == 200) {
          this.email = 0;
        }
      })
      .catch(response => {
        Vue.toasted.error('Algo Correu Mal...');
      });
    }
  }
};
</script>
