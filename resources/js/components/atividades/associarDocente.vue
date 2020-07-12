<template>
  <v-card>
    <v-card-title> Associar Docente </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Docente"
              outlined
              :items="docentes"
              item-value="id"
              item-text="aux"
              v-model="docente.docente"
              :error-messages="docenteErrors"
              @input="$v.docente.docente.$touch()"
              @blur="$v.docente.docente.$touch()"
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
              v-model="docente.descricao_participacao"
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
  mixins: [validationMixin],
  validations: {
    docente: {
      docente: { required },
    }
  },
  data() {
    return {
      docentes: [],
      docente: {},
	  email: {},
    };
  },
  computed: {
    docenteErrors() {
      const errors = [];
      if (!this.$v.docente.docente.$dirty) return errors;
      !this.$v.docente.docente.required && errors.push("É obrigatório escolher um docente");
      return errors;
    },
  },
  mounted(){
    this.getDocentes();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getDocentes() {
      axios.get(`api/docentes`).then(response => {
        this.docentes = response.data.data;
        this.docentes.forEach(docente => {
          docente.aux = "Nome: " + docente.nome + ", email: " + docente.email;
        });
      })
      .catch(response => {
        this.docentes = [];
      })
    },
    associar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.docentes.forEach(docente => {			
        if(this.docente.docente == docente.id) {
          this.email.email = docente.email;
        }
	    })
      this.$emit("associar", this.docente, this.email);
    }
  }
};
</script>
