<template>
  <v-card>
    <v-card-title> Editar Inquérito </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form lazy-validation>
        <v-row dense>
          <v-col>
            <v-text-field label="Nome"
              outlined
              v-model="codigo.nome"
              dense
              @keypress.enter.stop.prevent="create"
              :error-messages="nomeErrors"
              @input="$v.codigo.nome.$touch()"
              @blur="$v.codigo.nome.$touch()"></v-text-field>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-select label="Validade"
              outlined
              :items="validades"
              item-value="validade"
              v-model="codigo.validade"
              :error-messages="validadeErrors"
              @input="$v.codigo.validade.$touch()"
              @blur="$v.codigo.validade.$touch()"
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
import { required, maxLength } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    codigo: {
      nome: { required, maxLength: maxLength(255) },
      validade: { required }
    }
  },
  props: ["codigo"],
  data() {
    return {
      validades: [
        { validade: 1, text: "Válido" },
        { validade: 0, text: "Inválido" },
      ],
    };
  },
  computed: {
    nomeErrors() {
      const errors = [];
      if (!this.$v.codigo.nome.$dirty) return errors;
      !this.$v.codigo.nome.maxLength && errors.push("O nome só pode ter no máximo 255 carateres");
      !this.$v.codigo.nome.required && errors.push("O nome é obrigatório");
      return errors;
    },
    validadeErrors () {
      const errors = []
      if (!this.$v.codigo.validade.$dirty) return errors;
      !this.$v.codigo.validade.required && errors.push('A localização é obrigatória');
      return errors
    }
  },
  methods: {
    close() {
      this.$emit("close");
    },
    save() {
      this.$v.$touch();
      if(this.$v.$error) {
        return;
      }
      this.$emit("save", this.codigo);
    },
  },
}
</script>
