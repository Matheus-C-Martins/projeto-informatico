<template>
  <v-card>
    <v-card-title> Eliminar Utilizador </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Utilizador"
              @keypress.enter.stop.prevent="remove"
              v-model="user.id"
              :error-messages="idErrors"
              outlined
              dense
              :items="users"
              item-value="id"
              item-text="aux"
              @input="$v.user.id.$touch()"
              @blur="$v.user.id.$touch()">
            </v-select>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary block" @click.stop.prevent="remove"> Remover </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, maxLength } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    user: {
      id: { required },
    }
  },
  data() {
    return {
      users: [],
      user: {},
    };
  },
  computed: {
    idErrors() {
      const errors = [];
      if (!this.$v.user.id.$dirty) return errors;
      !this.$v.user.id.required && errors.push("Precisa de escolher um utilizador para eliminar");
      return errors;
    },
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      axios.get(`api/users`).then(response => {
        this.users = response.data.data;
      })
      .catch(response => {
        this.users = [];
      })
    },
    close() {
      this.$emit("close");
    },
    remove() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("remove", this.user);
    }
  }
};
</script>