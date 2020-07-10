<template>
  <v-card>
    <v-card-title> Remover Sala </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Sala"
              @keypress.enter.stop.prevent="remove"
              v-model="sala.id"
              :error-messages="idErrors"
              outlined
              dense
              :items="salas"
              item-value="id"
              item-text="nome"
              @input="$v.sala.id.$touch()"
              @blur="$v.sala.id.$touch()">
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
    sala: {
      id: { required },
    }
  },
  data() {
    return {
      salas: [],
      sala: {},
    };
  },
  computed: {
    idErrors() {
      const errors = [];
      if (!this.$v.sala.id.$dirty) return errors;
      !this.$v.sala.id.required && errors.push("Precisa de escolher uma sala para remover");
      return errors;
    },
  },
  mounted() {
    this.getSalas();
  },
  methods: {
    getSalas() {
      axios.get(`api/salas`).then(response => {
        this.salas = response.data.data;
      })
      .catch(response => {
        this.salas = [];
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
      this.$emit("remove", this.sala);
    }
  }
};
</script>