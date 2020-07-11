<template>
  <v-card>
    <v-card-title> Precisa de ajuda? </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-checkbox hide-details color="green" :disabled="disabled" v-model="sim" :label="`Sim`"></v-checkbox>
      <v-checkbox hide-details color="red" v-if="!sim" v-model="nao" :label="`Não`"></v-checkbox>
      <span v-else>
        <p class="mt-4 subheading text-left font-weight-bold"> O que é procura? </p>
        <v-select
          color="green"
          v-model="informacao"
          :items="informacoes"
          item-value="info"
          outlined
          dense>
        </v-select>
        <span v-if="informacao === 0">
          <p> Atualmente, o Departamento de Engenharia Informática tem a seu encargo 4 cursos técnico superior profissional, 2 licenciaturas e 2 mestrados.<br>
              Os cursos técnico superior profissional e os mestrados têm duração de 2 anos (4 semestres) e as licenciaturas de 3 anos (6 semestres)<br>
              Para mais informações aceda à aba "Cursos" presente no topo da página. </p>
        </span>
        <span v-else-if="informacao === 1">
          <p> O Departamento de Engenharia Informática organiza, atualmente, quatro tipos de atividades: Dia @ ESTG, Workshops, Seminários e Visitas à escola.<br>
              Para além destas atividades, o DEI organiza, também, uma academia de verão onde os participantes poderão experienciar o funcionamento de uma faculdade
              durante algumas semanas.<br>
              As atividades organizadas pelo Departamento de Engenharia Informática são uma excenlente oportunidade para esclarecer algumas dúvidas sobre, por exemplo,
              que curso superior ingressar.<br>
              Para mais informações aceda à aba "Atividades" presente no topo da página. </p>
        </span>
        <span v-else-if="informacao === 2">
          <p> A aplicação DEI@Tour foi desenvolvida no âmbito da unidade curricular de projeto informático e com o objetivo de auxiliar o Departamento de Engenharia Informática
              na organização de atividades.<br>
              Para mais informações aceda à aba "Sobre" presente no topo da página. </p>
        </span>
      </span>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      sim: false,
      nao: false,
      disabled: false,
      informacao: -1,
      informacoes: [
        { info: 0, text: "Informação sobre os cursos" },
        { info: 1, text: "Informação sobre as atividades" },
        { info: 2, text: "Informação sobre a aplicação" },
      ],
    };
  },
  methods: {
    close() {
      this.$emit("close");
    },
  },
  watch: {
    sim(val) {
      this.disabled = true;
      return val;
    },
    nao(val) {
      this.close();
    },
    informacao(val) {
      val
    }
  }
};
</script>
