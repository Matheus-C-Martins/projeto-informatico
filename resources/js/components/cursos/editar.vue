<template>
  <v-card>
    <v-card-title> Editar Curso </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Abreviatura"
              outlined
              v-model="curso.abreviatura"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="abreviaturaErrors"
              @input="$v.curso.abreviatura.$touch()"
              @blur="$v.curso.abreviatura.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="curso.nome"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="nomeErrors"
              @input="$v.curso.nome.$touch()"
              @blur="$v.curso.nome.$touch()"></v-text-field>
          </v-col>
        </v-row> 
        <v-row dense>
          <v-col>
            <v-select label="Tipo"
              outlined
              :items="tipoCursos"
              item-value="id"
              item-text="nome"
              v-model="curso.tipo"
              :error-messages="tipoErrors"
              @input="$v.curso.tipo.$touch()"
              @blur="$v.curso.tipo.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col> 
          <v-col>
            <v-text-field label="Contato"
              outlined
              v-model="curso.contato"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="contatoErrors"
              @input="$v.curso.contato.$touch()"
              @blur="$v.curso.contato.$touch()"></v-text-field>
          </v-col> 
        </v-row> 
        <v-row dense>
          <v-col>
            <v-text-field label="Vagas"
              outlined
              v-model="curso.vagas"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="vagasErrors"
              @input="$v.curso.vagas.$touch()"
              @blur="$v.curso.vagas.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-text-field label="Semestres"
              outlined
              v-model="curso.semestres"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="semestresErrors"
              @input="$v.curso.semestres.$touch()"
              @blur="$v.curso.semestres.$touch()"></v-text-field>
          </v-col> 
          <v-col>
            <v-text-field label="ECTS"
              outlined
              v-model="curso.ECTS"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="ectsErrors"
              @input="$v.curso.ECTS.$touch()"
              @blur="$v.curso.ECTS.$touch()"></v-text-field>
          </v-col> 
        </v-row>
        <v-row dense>
          <v-col>
            <v-textarea label="Objetivos"
              clearable
              outlined
              v-model="curso.objetivos"
              dense
              hide-details
              @keypress.enter.stop.prevent="save"
              :error-messages="objetivosErrors"
              @input="$v.curso.objetivos.$touch()"
              @blur="$v.curso.objetivos.$touch()"
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
import { required, maxValue, minValue, email, helpers } from "vuelidate/lib/validators";
export const numberRegex = helpers.regex('numberRegex', /^[0-9]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    curso: {
      abreviatura: { required },
      nome: { required },
      tipo: { required, maxValue: maxValue(99), minValue: minValue(1), numberRegex },
      semestres: { required, maxValue: maxValue(99), minValue: minValue(1), numberRegex },
      ECTS: { required, maxValue: maxValue(999), minValue: minValue(1), numberRegex },
      vagas: { required, maxValue: maxValue(999), minValue: minValue(1), numberRegex },
      contato: { required, email },
      objetivos: { required },
    }
  },
  props: [
    'curso',
  ],
  data: function() {
    return {
      tipoCursos: [],
      tipoCurso: {},
    };
  },
  computed: {
    abreviaturaErrors() {
      const errors = [];
      if (!this.$v.curso.abreviatura.$dirty) return errors;
      !this.$v.curso.abreviatura.required && errors.push("É obrigatório escolher uma abreviatura");
      return errors;
    },
    nomeErrors() {
      const errors = [];
      if (!this.$v.curso.nome.$dirty) return errors;
      !this.$v.curso.nome.required && errors.push("É obrigatório escolher um nome");
      return errors;
    },
    tipoErrors() {
      const errors = []
      if (!this.$v.curso.tipo.$dirty) return errors;
      !this.$v.curso.tipo.required && errors.push('O tipo de curso é obrigatório');
      !this.$v.curso.tipo.numberRegex && errors.push('O tipo de curso tem de ser um número');
      !this.$v.curso.tipo.maxValue && errors.push('O tipo de curso só pode ter, no máximo, 2 digitos');
      !this.$v.curso.tipo.minValue && errors.push('O tipo de curso tem de ter, no mínimo, 1 digito');
      return errors
    },
    semestresErrors() {
      const errors = []
      if (!this.$v.curso.semestres.$dirty) return errors;
      !this.$v.curso.semestres.required && errors.push('O número de semestres é obrigatório');
      !this.$v.curso.semestres.numberRegex && errors.push('O número de semestres tem de ser um número');
      !this.$v.curso.semestres.maxValue && errors.push('O número de semestres só pode ter, no máximo, 2 digitos');
      !this.$v.curso.semestres.minValue && errors.push('O número de semestres tem de ter, no mínimo, 1 digito');
      return errors
    },
    ectsErrors() {
      const errors = []
      if (!this.$v.curso.ECTS.$dirty) return errors;
      !this.$v.curso.ECTS.required && errors.push('O número de ECTS é obrigatório');
      !this.$v.curso.ECTS.numberRegex && errors.push('O número de ECTS tem de ser um número');
      !this.$v.curso.ECTS.maxValue && errors.push('O número de ECTS só pode ter, no máximo, 3 digitos');
      !this.$v.curso.ECTS.minValue && errors.push('O número de ECTS tem de ter, no mínimo, 1 digito');
      return errors
    },
    vagasErrors() {
      const errors = []
      if (!this.$v.curso.vagas.$dirty) return errors;
      !this.$v.curso.vagas.required && errors.push('O número de vagas é obrigatório');
      !this.$v.curso.vagas.numberRegex && errors.push('O número de vagas tem de ser um número');
      !this.$v.curso.vagas.maxValue && errors.push('O número de vagas só pode ter, no máximo, 3 digitos');
      !this.$v.curso.vagas.minValue && errors.push('O número de vagas tem de ter, no mínimo, 1 digito');
      return errors
    },
    contatoErrors() {
      const errors = [];
      if (!this.$v.curso.contato.$dirty) return errors;
      !this.$v.curso.contato.required && errors.push('É obrigatório escolher um contato');
      !this.$v.curso.contato.email && errors.push('Tem de inserir um email válido');
      return errors;
    },
    objetivosErrors() {
      const errors = [];
      if (!this.$v.curso.objetivos.$dirty) return errors;
      !this.$v.curso.objetivos.required && errors.push("É obrigatório escolher um objetivo");
      return errors;
    },
  },
  mounted() {
    this.getTipoCursos();
  },
  methods: {
    getTipoCursos() {
      axios.get(`api/tipoCursos`).then(response => {
        this.tipoCursos = response.data.data;
        this.tipoCursos.forEach(aux => {
          if(aux.nome === this.curso.tipo){
            this.curso.tipo = aux.id;
          }
        });
      })
      .catch(response => {
        this.tipoCursos = [];
      })
    },
    close() {
      this.$emit("close");
    },
    save() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("save", this.curso);
    },
  },
}
</script>
