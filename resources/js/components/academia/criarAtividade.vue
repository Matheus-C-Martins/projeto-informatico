<template>
  <v-card>
    <v-card-title> Criar Atividade </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="atividade.nome"
              :error-messages="nomeErrors"
              @input="$v.atividade.nome.$touch()"
              @blur="$v.atividade.nome.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-select label="Ano da Academia de Verão"
              outlined
              :items="academias_de_verao"
              item-value="id"
              item-text="data"
              v-model="atividade.academia_de_verao"
              :error-messages="academia_de_veraoErrors"
              @input="$v.atividade.academia_de_verao.$touch()"
              @blur="$v.atividade.academia_de_verao.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
          </v-col>
          <v-col>
            <v-select label="Tipo de Atividade"
              outlined
              :items="tipo"
              item-value="tipo"
              v-model="atividade.tipo"
              :error-messages="tipoErrors"
              @input="$v.atividade.tipo.$touch()"
              @blur="$v.atividade.tipo.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-textarea label="Observações"
              clearable
              outlined
              v-model="atividade.observacoes"
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
      <button class="btn btn-secondary" @click.stop.prevent="create"> Criar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    atividade: {
      academia_de_verao: { required },
      nome: { required },
      tipo: { required },
    }
  },
  props: ["atividade"],
  data: function() {
    return {
      academias_de_verao: [],
      academia_de_verao: {},
      contactos: [],
      contacto: {},
      tipo: [
        { tipo: "aula", text: "Aula" },
        { tipo: "workshop", text: "Workshop" },
      ],
    };
  },
  computed: {
    academia_de_veraoErrors() {
      const errors = [];
      if (!this.$v.atividade.academia_de_verao.$dirty) return errors;
      !this.$v.atividade.academia_de_verao.required && errors.push("É obrigatório escolher o ano da academia");
      return errors;
    },
    nomeErrors() {
      const errors = []
      if (!this.$v.atividade.nome.$dirty) return errors;
      !this.$v.atividade.nome.required && errors.push('É obrigatório inserir um nome');
      return errors
    },
    tipoErrors() {
      const errors = [];
      if (!this.$v.atividade.tipo.$dirty) return errors;
      !this.$v.atividade.tipo.required && errors.push("É obrigatório escolher o tipo da atividade");
      return errors;
    },
  },
  mounted() {
    this.getAcademias();
  },
  methods: {
    getAcademias() {
      axios.get(`api/academias`).then(response => {
        this.academias_de_verao = response.data.data;
      })
      .catch(response => {
        this.academias_de_verao = [];
      })
    },
    close() {
      this.$emit("close");
    },
    create() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("create", this.atividade);
    }
  }
};
</script>
