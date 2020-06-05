<template>
  <v-card>
    <v-card-title> Associar Escola </v-card-title>
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
              @keypress.enter.stop.prevent="associar"
            ></v-select>
          </v-col>
          <v-col v-if="contacto.tipo == 'Email'">
            <v-select label="Email"
              outlined
              :items="contactos"
              item-value="id"
              item-text="email"
              v-model="contacto.contacto"
              :error-messages="contactoErrors"
              @input="$v.contacto.contacto.$touch()"
              @blur="$v.contacto.contacto.$touch()"
              dense
              @keypress.enter.stop.prevent="associar"
            ></v-select>
          </v-col>
          <v-col v-if="contacto.tipo == 'Telefone'">
            <v-select label="Telefone"
              outlined
              :items="contactos"
              item-value="id"
              item-text="telefone"
              v-model="contacto.contacto"
              :error-messages="contactoErrors"
              @input="$v.contacto.contacto.$touch()"
              @blur="$v.contacto.contacto.$touch()"
              dense
              @keypress.enter.stop.prevent="associar"
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
      <button class="btn btn-secondary" @click.stop.prevent="associar"> Associar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  props: ["escola"],
  mixins: [validationMixin],
  validations: {
    contacto: {
      contacto: { required },
      tipo: { required },
    }
  },
  data: function() {
    return {
      tipos: [
        { tipo: "Telefone", text: "Telefone" },
        { tipo: "Email", text: "Email" },
      ],
      contactos: [],
      contacto: {},
    };
  },
  computed: {
    contactoErrors() {
      const errors = [];
      if (!this.$v.contacto.contacto.$dirty) return errors;
      !this.$v.contacto.contacto.required && errors.push("É obrigatório escolher um contacto");
      return errors;
    },
    tipoErrors () {
      const errors = []
      if (!this.$v.contacto.tipo.$dirty) return errors;
      !this.$v.contacto.tipo.required && errors.push('É obrigatório escolher o tipo de contacto recebido');
      return errors
    },
  },
  mounted(){
    this.getContactos();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getContactos() {
      axios.get(`api/contactos`).then(response => {
        this.contactos = response.data.data;
      })
      .catch(response => {
        this.contactos = [];
      })
    },
    associar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.contacto.escola = this.escola.id;
      this.$emit("associar", this.contacto);
    }
  }
};
</script>
