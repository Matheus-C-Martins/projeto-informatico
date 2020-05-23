<template>
  <v-card>
    <v-card-title> Editar Associado </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select v-if="contacto.tipo == 'Telefone'" label="Contacto"
              outlined
              :items="contactos"
              item-value="id"
              item-text="telefone"
              v-model="contacto.contacto"
              :error-messages="contactoErrors"
              @input="$v.contacto.contacto.$touch()"
              @blur="$v.contacto.contacto.$touch()"
              dense
              @keypress.enter.stop.prevent="editar"
            ></v-select>
            <v-select v-if="contacto.tipo == 'Email'" label="Contacto"
              outlined
              :items="contactos"
              item-value="id"
              item-text="email"
              v-model="contacto.contacto"
              :error-messages="contactoErrors"
              @input="$v.contacto.contacto.$touch()"
              @blur="$v.contacto.contacto.$touch()"
              dense
              @keypress.enter.stop.prevent="editar"
            ></v-select>
          </v-col>
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
              @keypress.enter.stop.prevent="editar"
            ></v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Descrição"
              clearable
              outlined
              v-model="contacto.descricao"
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
  props: ["escola", "contacto"],
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
        this.contactos.forEach(aux => {
          if(this.contacto.telefone !== "-----" && aux.telefone === this.contacto.telefone && this.contacto.email === "-----" ){
            this.contacto.contacto = aux.id;
          } else if (this.contacto.email !== "-----" && aux.email === this.contacto.email && this.contacto.telefone === "-----" ){
            this.contacto.contacto = aux.id;
          }
        });
      })
      .catch(response => {
        this.contactos = [];
      })
    },
    editar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.contacto.escola = this.escola.id;
      this.$emit("save", this.contacto);
    }
  }
};
</script>
