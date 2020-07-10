<template>
  <v-card>
    <v-card-title> Associar Atividade </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-select label="Atividade"
              outlined
              :items="atividades"
              item-value="id"
              item-text="aux"
              v-model="atividade.atividade"
              :error-messages="atividadeErrors"
              @input="$v.atividade.atividade.$touch()"
              @blur="$v.atividade.atividade.$touch()"
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
              v-model="atividade.descricao_participacao"
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
    atividade: {
      atividade: { required },
    }
  },
  data() {
    return {
      atividades: [],
      atividade: {},
    };
  },
  computed: {
    atividadeErrors() {
      const errors = [];
      if (!this.$v.atividade.atividade.$dirty) return errors;
      !this.$v.atividade.atividade.required && errors.push("É obrigatório escolher uma atividade");
      return errors;
    },
  },
  mounted(){
    this.getAtividades();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    getAtividades() {
      axios.get(`api/atividades`).then(response => {
        this.atividades = response.data.data;
        this.atividades.forEach(a => {
          a.aux = "Tipo da atividade: " + a.tipo_atividade+", data: "+a.data+", hora: "+a.hora;
        });
      })
      .catch(response => {
        this.atividades = [];
      })
    },
    associar() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("associar", this.atividade);
    }
  }
};
</script>
