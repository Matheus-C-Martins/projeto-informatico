<template>
  <v-card>
    <v-card-title> Editar Atividade </v-card-title>
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
              @keypress.enter.stop.prevent="save"
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
              @keypress.enter.stop.prevent="save"
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
              @keypress.enter.stop.prevent="save"
            ></v-text-field>
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
      <button class="btn btn-secondary" @click.stop.prevent="save"> Editar </button>
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
    }
  },
  props: [
    'atividade',
  ],
  data: function() {
    return {
      escolas: [],
      escola: {},
      contactos: [],
      contacto: {},
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
      !this.$v.atividade.contacto.required && errors.push("É obrigatório escolher uma contacto");
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
    }
  },
  mounted() {
    this.getEscolas();
    this.getContactos();
  },
  methods: {
    getEscolas() {
      axios.get(`api/escolas`).then(response => {
        this.escolas = response.data.data;
        this.escolas.forEach(aux => {
          if(aux.nome === this.atividade.escola){
            this.atividade.escola = aux.id;
          }
        });
      })
      .catch(response => {
        this.escolas = [];
      })
    },
    getContactos() {
      axios.get(`api/contactos`).then(response => {
        this.contactos = response.data.data;
        this.contactos.forEach(aux => {
          if(aux.telefone === this.atividade.telefone && aux.email === this.atividade.email){
            this.atividade.contacto = aux.id;
          }
        });
      })
      .catch(response => {
        this.contactos = [];
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
      this.atividade.numero_de_alunos = this.atividade.numero_alunos;
      this.$emit("save", this.atividade);
    },
  },
}
</script>
