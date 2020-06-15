<template>
  <v-card>
    <v-card-title> Criar Atividade </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Escola"
              outlined
              :items="escolas"
              item-value="id"
              item-text="nome"
              v-model="atividade.escola"
              :error-messages="escolaErrors"
              @input="$v.atividade.escola.$touch()"
              @blur="$v.atividade.escola.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
          </v-col>
          <v-col>
            <v-select label="Contacto"
              outlined
              :items="contactos"
              item-value="id"
              item-text="nome"
              v-model="atividade.contacto"
              :error-messages="contactoErrors"
              @input="$v.atividade.contacto.$touch()"
              @blur="$v.atividade.contacto.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
          </v-col>
          <v-col>
            <v-text-field label="Número de Alunos"
              outlined
              v-model="atividade.numero_alunos"
              :error-messages="numero_alunosErrors"
              @input="$v.atividade.numero_alunos.$touch()"
              @blur="$v.atividade.numero_alunos.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-select label="Ano Escolar"
              outlined
              :items="ano"
              item-value="escolar"
              v-model="atividade.ano"
              :error-messages="anoErrors"
              @input="$v.atividade.ano.$touch()"
              @blur="$v.atividade.ano.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-select>
          </v-col>
          <v-col>
            <v-text-field label="Turma"
              outlined
              v-model="atividade.turma"
              :error-messages="turmaErrors"
              @input="$v.atividade.turma.$touch()"
              @blur="$v.atividade.turma.$touch()"
              dense
              @keypress.enter.stop.prevent="create"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-menu
              ref="menuData"
              v-model="menuData"
              :close-on-content-click="false"
              :return-value.sync="atividade.data"
              transition="scale-transition"
              offset-y
              min-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Data da Atividade"
                  v-model="atividade.data"
                  outlined
                  dense
                  :error-messages="dataErrors"
                  @input="$v.atividade.data.$touch()"
                  @blur="$v.atividade.data.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="atividade.data" no-title>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menuData = false"> Cancelar </v-btn>
                <v-btn text color="primary" @click="$refs.menuData.save(atividade.data)"> Ok </v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-menu
              ref="menuTime"
              v-model="menuTime"
              :close-on-content-click="false"
              :return-value.sync="atividade.time"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Hora de Início da Atividade"
                  v-model="atividade.time"
                  outlined
                  dense
                  :error-messages="timeErrors"
                  @input="$v.atividade.time.$touch()"
                  @blur="$v.atividade.time.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title format="24hr" v-if="menuTime" v-model="atividade.time" full-width @click:minute="$refs.menuTime.save(atividade.time)">
              </v-time-picker>
            </v-menu>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-select label="Tipo de Atividade"
              outlined
              :items="tipo_de_atividade"
              item-value="tipo"
              v-model="atividade.tipo_de_atividade"
              :error-messages="tipo_de_atividadeErrors"
              @input="$v.atividade.tipo_de_atividade.$touch()"
              @blur="$v.atividade.tipo_de_atividade.$touch()"
              dense
              @keypress.enter.stop.prevent="save"
            ></v-select>
          </v-col>
          <v-col>
            <v-menu
              ref="menuDuracao"
              v-model="menuDuracao"
              :close-on-content-click="false"
              :return-value.sync="atividade.duracao"
              transition="scale-transition"
              offset-y
              min-width="290px"
              max-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Duração da Atividade"
                  v-model="atividade.duracao"
                  outlined
                  dense
                  :error-messages="duracaoErrors"
                  @input="$v.atividade.duracao.$touch()"
                  @blur="$v.atividade.duracao.$touch()"
                  readonly
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker no-title v-if="menuDuracao" v-model="atividade.duracao" full-width @click:minute="$refs.menuDuracao.save(atividade.duracao)">
              </v-time-picker>
            </v-menu>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-textarea label="Descrição"
              clearable
              outlined
              v-model="atividade.descricao"
              dense
              hide-details
              @keypress.enter.stop.prevent="save"
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
import { required, maxValue, minValue, helpers } from "vuelidate/lib/validators";
export const numberRegex = helpers.regex('numberRegex', /^[0-9]*$/);

export default {
  mixins: [validationMixin],
  validations: {
    atividade: {
      escola: { required },
      contacto: { required },
      numero_alunos: { required, maxValue: maxValue(999), minValue: minValue(1), numberRegex },
      ano: { required },
      turma: { required },
      data: { required },
      time: { required },
      duracao: { required },
      tipo_de_atividade: { required },
    }
  },
  props: ["atividade"],
  data: function() {
    return {
      escolas: [],
      escola: {},
      contactos: [],
      contacto: {},
      tipo_de_atividade: [
        { tipo: "diaESTG", text: "Dia ESTG" },
        { tipo: "workshop", text: "Workshop" },
        { tipo: "seminario", text: "Seminário" },
      ],
      ano: [
        { escolar: "12ºano", text: "12ºano" },
        { escolar: "11ºano", text: "11ºano" },
        { escolar: "10ºano", text: "10ºano" },
      ],
      menuData: false,
      menuTime: false,
      menuDuracao: false,
    };
  },
  computed: {
    escolaErrors() {
      const errors = [];
      if (!this.$v.atividade.escola.$dirty) return errors;
      !this.$v.atividade.escola.required && errors.push("É obrigatório escolher uma escola");
      return errors;
    },
    contactoErrors() {
      const errors = [];
      if (!this.$v.atividade.contacto.$dirty) return errors;
      !this.$v.atividade.contacto.required && errors.push("É obrigatório escolher um contacto");
      return errors;
    },
    numero_alunosErrors() {
      const errors = []
      if (!this.$v.atividade.numero_alunos.$dirty) return errors;
      !this.$v.atividade.numero_alunos.required && errors.push('O número de alunos é obrigatório');
      !this.$v.atividade.numero_alunos.numberRegex && errors.push('O número de alunos tem de ser um número');
      !this.$v.atividade.numero_alunos.maxValue && errors.push('O número de alunos só pode ter, no máximo, 3 digitos');
      !this.$v.atividade.numero_alunos.minValue && errors.push('O número de alunos tem de ter, no mínimo, 1 digito');
      return errors
    },
    anoErrors() {
      const errors = []
      if (!this.$v.atividade.ano.$dirty) return errors;
      !this.$v.atividade.ano.required && errors.push('O ano escolar é obrigatório');
      return errors
    },
    turmaErrors() {
      const errors = []
      if (!this.$v.atividade.turma.$dirty) return errors;
      !this.$v.atividade.turma.required && errors.push('A turma é obrigatória');
      return errors
    },
    dataErrors() {
      const errors = [];
      if (!this.$v.atividade.data.$dirty) return errors;
      !this.$v.atividade.data.required && errors.push("É obrigatório escolher uma data");
      return errors;
    },  
    timeErrors() {
      const errors = [];
      if (!this.$v.atividade.time.$dirty) return errors;
      !this.$v.atividade.time.required && errors.push("É obrigatório escolher uma hora de início");
      return errors;
    },
    tipo_de_atividadeErrors() {
      const errors = [];
      if (!this.$v.atividade.tipo_de_atividade.$dirty) return errors;
      !this.$v.atividade.tipo_de_atividade.required && errors.push("É obrigatório escolher o tipo da atividade");
      return errors;
    },
    duracaoErrors() {
      const errors = [];
      if (!this.$v.atividade.duracao.$dirty) return errors;
      !this.$v.atividade.duracao.required && errors.push("É obrigatório escolher uma duração para a atividade");
      return errors;
    },
  },
  mounted() {
    this.getEscolas();
    this.getContactos();
  },
  methods: {
    getEscolas() {
      axios.get(`api/escolas`).then(response => {
        this.escolas = response.data.data;
      })
      .catch(response => {
        this.escolas = [];
      })
    },
    getContactos() {
      axios.get(`api/contactos`).then(response => {
        this.contactos = response.data.data;
      })
      .catch(response => {
        this.contactos = [];
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
      this.atividade.numero_de_alunos = this.atividade.numero_alunos;
      this.atividade.duracao = this.atividade.duracao + ":00";
      this.atividade.data = this.atividade.data + " " + this.atividade.time + ":00";
      this.$emit("create", this.atividade);
    }
  }
};
</script>
