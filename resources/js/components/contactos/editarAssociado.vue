<template>
  <v-card>
    <v-card-title> Editar Associado </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Escola"
              outlined
              :items="escolas"
              item-value="id"
              item-text="nome"
              v-model="escola.escola"
              :error-messages="escolaErrors"
              @input="$v.escola.escola.$touch()"
              @blur="$v.escola.escola.$touch()"
              dense
              @keypress.enter.stop.prevent="editar"
            ></v-select>
          </v-col>
          <v-col>
            <v-select label="Tipo de Contacto"
              outlined
              :items="tipos"
              item-value="tipo"
              v-model="escola.tipo"
              :error-messages="tipoErrors"
              @input="$v.escola.tipo.$touch()"
              @blur="$v.escola.tipo.$touch()"
              dense
              @keypress.enter.stop.prevent="editar"
            ></v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Descrição"
              clearable
              outlined
              v-model="escola.descricao"
              dense
              hide-details
              @keypress.enter.stop.prevent="editar"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="editar"> Editar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  props: ["contacto", "escola"],
  mixins: [validationMixin],
  validations: {
    escola: {
      escola: { required },
      tipo: { required },
    }
  },
  data: function() {
    return {
      tipos: [
        { tipo: "Telefone", text: "Telefone" },
        { tipo: "Email", text: "Email" },
      ],
      escolas: [],
    };
  },
  computed: {
    escolaErrors() {
      const errors = [];
      if (!this.$v.escola.escola.$dirty) return errors;
      !this.$v.escola.escola.required && errors.push("É obrigatório escolher uma escola");
      return errors;
    },
    tipoErrors () {
      const errors = []
      if (!this.$v.escola.tipo.$dirty) return errors;
      !this.$v.escola.tipo.required && errors.push('É obrigatório escolher o tipo de contacto recebido');
      return errors
    },
  },
  mounted(){
    this.getEscolas();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getEscolas() {
      axios.get(`api/escolas`).then(response => {
        this.escolas = response.data.data;
        this.escolas.forEach(aux => {
          if(aux.nome === this.escola.escola){
            this.escola.escola = aux.id;
          }
        });
      })
      .catch(response => {
        this.escolas = [];
      });
    },
    editar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.escola.contacto = this.contacto.id;
      this.$emit("save", this.escola);
    }
  }
};
</script>
