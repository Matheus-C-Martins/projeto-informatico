<template>
  <v-card>
    <v-card-title> Marcar Como Efetuado </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Tipo de Contacto"
              outlined
              :items="tipos"
              item-value="tipo"
              v-model="contacto.tipo"
              :error-messages="tipoErrors"
              @input="$v.contacto.tipo.$touch()"
              @blur="$v.contacto.tipo.$touch()"
              dense
              @keypress.enter.stop.prevent="marcar"
            ></v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-textarea label="Descrição"
              clearable
              outlined
              v-model="contacto.descricao"
              dense
              hide-details
            ></v-textarea>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="marcar"> Marcar Efetuado </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    contacto: {
      tipo: { required },
    }
  },
  data: function() {
    return {
      contacto: {},
      tipos: [
        { tipo: "Telefone", text: "Telefone" },
        { tipo: "Email", text: "Email" },
      ],
    };
  },
  computed: {
    tipoErrors () {
      const errors = []
      if (!this.$v.contacto.tipo.$dirty) return errors;
      !this.$v.contacto.tipo.required && errors.push('É indicar como o contacto foi efetuado');
      return errors
    },
  },
  methods: {
    close() {
      this.contacto = {};
      this.$emit("close");
    },
    marcar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("marcar", this.contacto);
    }
  }
};
</script>
