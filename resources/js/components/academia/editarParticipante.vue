<template>
  <v-card>
    <v-card-title> Editar Participante </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="participante.nome"
              :error-messages="nomeErrors"
              @input="$v.participante.nome.$touch()"
              @blur="$v.participante.nome.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-select label="Escola"
              outlined
              :items="escolas"
              item-value="id"
              item-text="nome"
              v-model="participante.escola"
              :error-messages="escolaErrors"
              @input="$v.participante.escola.$touch()"
              @blur="$v.participante.escola.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
          </v-col>
          <v-col>
            <v-text-field label="Região"
              outlined
              v-model="participante.regiao"
              :error-messages="regiaoErrors"
              @input="$v.participante.regiao.$touch()"
              @blur="$v.participante.regiao.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Ano"
              outlined
              v-model="participante.ano"
              :error-messages="anoErrors"
              @input="$v.participante.ano.$touch()"
              @blur="$v.participante.ano.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-select label="Curso"
              outlined
              :items="cursos"
              item-value="tipo"
              v-model="participante.curso"
              :error-messages="cursoErrors"
              @input="$v.participante.curso.$touch()"
              @blur="$v.participante.curso.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col>
          <v-col>
            <v-menu
              ref="menuData"
              v-model="menuData"
              :close-on-content-click="false"
              :return-value.sync="participante.data_de_nascimento"
              transition="scale-transition"
              offset-y
              min-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Data de Nascimento"
                  v-model="participante.data_de_nascimento"
                  outlined
                  dense
                  :error-messages="data_de_nascimentoErrors"
                  @input="$v.participante.data_de_nascimento.$touch()"
                  @blur="$v.participante.data_de_nascimento.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="participante.data_de_nascimento" no-title @click:date="$refs.menuData.save(participante.data_de_nascimento)">
              </v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-text-field label="Telefone do Aluno"
              outlined 
              v-model="participante.contacto_do_aluno" 
              dense 
              @keypress.enter.stop.prevent="create"
              :error-messages="contacto_do_alunoErrors"
              @input="$v.participante.contacto_do_aluno.$touch()"
              @blur="$v.participante.contacto_do_aluno.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-select label="Encarregado de Educação"
              outlined
              :items="ee"
              item-value="nome"
              v-model="participante.encarregado_de_educacao"
              :error-messages="encarregado_de_educacaoErrors"
              @input="$v.participante.encarregado_de_educacao.$touch()"
              @blur="$v.participante.encarregado_de_educacao.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col>
          <v-col>
            <v-text-field label="Telefone do Encarregado de Educação"
              outlined 
              v-model="participante.contacto_do_ee" 
              dense 
              @keypress.enter.stop.prevent="create"
              :error-messages="contacto_do_eeErrors"
              @input="$v.participante.contacto_do_ee.$touch()"
              @blur="$v.participante.contacto_do_ee.$touch()"></v-text-field>
          </v-col>
          <v-col>
            <v-select label="Ano da Academia de Verão"
              outlined
              :items="academias_de_verao"
              item-value="id"
              item-text="data"
              v-model="participante.academia_de_verao"
              :error-messages="academia_de_veraoErrors"
              @input="$v.participante.academia_de_verao.$touch()"
              @blur="$v.participante.academia_de_verao.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
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
import { required, maxLength, minLength, helpers } from "vuelidate/lib/validators";
export const alphaDiacritic = helpers.regex('alphaDiacritic', /^[a-zA-ZÀ-ž\s]*$/);
export const telefoneRegex = helpers.regex('telefoneRegex', /^[0-9]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    participante: {
      academia_de_verao: { required },
      nome: { required, alphaDiacritic, maxLength: maxLength(255) },
      regiao: { required, alphaDiacritic, maxLength: maxLength(255) },
      curso: { required },
      escola: { required },
      ano: { required },
      data_de_nascimento: { required },
      contacto_do_ee: {required, maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      contacto_do_aluno: {required, maxLength: maxLength(9), minLength: minLength(9), telefoneRegex},
      encarregado_de_educacao: { required }
    }
  },
  props: ['participante'],
  data: function() {
    return {
      menuData: false,
      academias_de_verao: [],
      academia_de_verao: {},
      cursos: [
        { tipo: "AV", text: "AV" },
        { tipo: "CT", text: "CT" },
        { tipo: "CSE", text: "CSE" },
        { tipo: "LH", text: "LH" },
        { tipo: "Profissional", text: "Profissional" },
      ],
      ee: [
        { nome: "Mãe", text: "Mãe" },
        { nome: "Pai", text: "Pai" },
        { nome: "Avó", text: "Avó" },
        { nome: "Avô", text: "Avô" },
        { nome: "Tia", text: "Tia" },
        { nome: "Tio", text: "Tio" },
      ],
      escolas: [],
      escola: {},
    };
  },
  computed: {
    academia_de_veraoErrors() {
      const errors = [];
      if (!this.$v.participante.academia_de_verao.$dirty) return errors;
      !this.$v.participante.academia_de_verao.required && errors.push("É obrigatório escolher o ano da academia");
      return errors;
    },
    nomeErrors() {
      const errors = [];
      if (!this.$v.participante.nome.$dirty) return errors;
      !this.$v.participante.nome.alphaDiacritic && errors.push("O nome só pode conter espaços ou letras");
      !this.$v.participante.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.participante.nome.required && errors.push("É obrigatótio indicar o nome do participante");
      return errors;
    },
    regiaoErrors() {
      const errors = [];
      if (!this.$v.participante.regiao.$dirty) return errors;
      !this.$v.participante.regiao.alphaDiacritic && errors.push("A regiao só pode conter espaços ou letras");
      !this.$v.participante.regiao.maxLength && errors.push("A regiao só pode ter no máximo 255 carateres");
      !this.$v.participante.regiao.required && errors.push("É obrigatório indicar a região do participante");
      return errors;
    },
    cursoErrors() {
      const errors = [];
      if (!this.$v.participante.curso.$dirty) return errors;
      !this.$v.participante.curso.required && errors.push("É obrigatório indicar o curso do participante");
      return errors;
    },
    escolaErrors() {
      const errors = [];
      if (!this.$v.participante.escola.$dirty) return errors;
      !this.$v.participante.escola.required && errors.push("É obrigatório indicar a escola do participante");
      return errors;
    },
    anoErrors() {
      const errors = [];
      if (!this.$v.participante.ano.$dirty) return errors;
      !this.$v.participante.ano.required && errors.push("É obrigatório indicar o ano que o participante frequentou");
      return errors;
    },
    encarregado_de_educacaoErrors() {
      const errors = [];
      if (!this.$v.participante.encarregado_de_educacao.$dirty) return errors;
      !this.$v.participante.encarregado_de_educacao.required && errors.push("É obrigatório indicar um ecarregado de educação");
      return errors;
    },
    data_de_nascimentoErrors() {
      const errors = [];
      if (!this.$v.participante.data_de_nascimento.$dirty) return errors;
      !this.$v.participante.data_de_nascimento.required && errors.push("É obrigatório indicar a data de nascimento do participante");
      return errors;
    },
    contacto_do_eeErrors () {
      const errors = []
      if (!this.$v.participante.contacto_do_ee.$dirty) return errors;
      !this.$v.participante.contacto_do_ee.required && errors.push("É obrigatório indicar o contacto do encarregado de educação");
      !this.$v.participante.contacto_do_ee.telefoneRegex && errors.push('O contacto do encarregado de educação só pode conter números');
      !this.$v.participante.contacto_do_ee.maxLength && errors.push('O contacto do encarregado de educação só pode ter 9 digitos');
      !this.$v.participante.contacto_do_ee.minLength && errors.push('O contacto do encarregado de educação só pode ter 9 digitos');
      return errors
    },
    contacto_do_alunoErrors () {
      const errors = []
      if (!this.$v.participante.contacto_do_aluno.$dirty) return errors;
      !this.$v.participante.contacto_do_aluno.required && errors.push("É obrigatório indicar o contacto do aluno");
      !this.$v.participante.contacto_do_aluno.telefoneRegex && errors.push('O contacto do aluno só pode conter números');
      !this.$v.participante.contacto_do_aluno.maxLength && errors.push('O contacto do aluno só pode ter 9 digitos');
      !this.$v.participante.contacto_do_aluno.minLength && errors.push('O contacto do aluno só pode ter 9 digitos');
      return errors
    },
  },
  mounted() {
    this.getAcademias();
    this.getEscolas();
  },
  methods: {
    getAcademias() {
      axios.get(`api/academias`).then(response => {
        this.academias_de_verao = response.data.data;
        this.academias_de_verao.forEach(aux => {
          if(aux.data === this.participante.academia_de_verao){
            this.participante.academia_de_verao = aux.id;
          }
        });
      })
      .catch(response => {
        this.academias_de_verao = [];
      })
    },
    getEscolas() {
      axios.get(`api/escolas`).then(response => {
        this.escolas = response.data.data;
        this.escolas.forEach(aux => {
          if(aux.nome === this.participante.escola){
            this.participante.escola = aux.id;
          }
        });
      })
      .catch(response => {
        this.escolas = [];
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
      this.$emit("save", this.participante);
    },
  },
}
</script>
