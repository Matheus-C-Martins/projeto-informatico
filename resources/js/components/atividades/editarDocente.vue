<template>
  <v-card>
    <v-card-title> Editar Docente </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-textarea label="Descrição"
              clearable
              outlined
              v-model="docente.descricao_participacao"
              :error-messages="docenteErrors"
              @input="$v.docente.descricao_participacao.$touch()"
              @blur="$v.docente.descricao_participacao.$touch()"
              dense
            ></v-textarea>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="save"> Editar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  props: ["docente"],
  mixins: [validationMixin],
  validations: {
    docente: {
      descricao_participacao: { required },
    }
  },
  data() {
    return {
      docentes: [],
    };
  },
  computed: {
    docenteErrors() {
      const errors = [];
      if (!this.$v.docente.descricao_participacao.$dirty) return errors;
      !this.$v.docente.descricao_participacao.required && errors.push("Não pode deixar este campo vazio");
      return errors;
    }
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
      })
      .catch(response => {
        this.docentes = [];
      })
    },
    save() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }      
      this.$emit("save", this.docente);
    }
  }
};
</script>
