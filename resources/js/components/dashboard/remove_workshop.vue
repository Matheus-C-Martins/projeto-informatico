<template>
  <v-card>
    <v-card-title> Adicionar workshop </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="workshop"
              @keypress.enter.stop.prevent="remove"
              v-model="workshop.id"
              :error-messages="idErrors"
              outlined
              dense
              :items="workshops"
              item-value="id"
              item-text="nome"
              @input="$v.workshop.id.$touch()"
              @blur="$v.workshop.id.$touch()">
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
    workshop: {
      id: { required },
    }
  },
  data() {
    return {
      workshops: [],
      workshop: {},
    };
  },
  computed: {
    idErrors() {
      const errors = [];
      if (!this.$v.workshop.id.$dirty) return errors;
      !this.$v.workshop.id.required && errors.push("Precisa de escolher um workshop para remover");
      return errors;
    },
  },
  mounted() {
    this.getWorkshops();
  },
  methods: {
    getWorkshops() {
      axios.get(`api/workshops`).then(response => {
        this.workshops = response.data.data;
      })
      .catch(response => {
        this.workshops = [];
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
      this.$emit("remove", this.workshop);
    }
  }
};
</script>