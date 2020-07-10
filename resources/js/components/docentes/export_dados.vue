<template>
  <v-card>
    <v-card-title> Exportar Dados </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-row dense justify="center" align="center">
        <v-col>
          <v-select label="Ano da Academia de Verão"
            :items="academias"
            item-value="id"
            item-text="data"
            v-model="academia"
            hide-details
            outlined
            dense
          ></v-select>
        </v-col>
        <v-col>
          <v-select label="Modelo"
            outlined
            :items="modelos"
            item-value="modelo"
            hide-details
            v-model="exportType"
            dense
          ></v-select>
        </v-col>
        <v-col md="auto" >
          <button @click.stop.prevent="exportCsv" class="btn btn-secondary block"> Exportar </button>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      exportType: 0,
      academias: [],
      academia: null,
      modelos: [
        { modelo: "1", text: "Participantes" },
        { modelo: "2", text: "Atividades" },
      ]
    };
  },
  mounted(){
    this.getAcademias();
  },
  methods: {
    close() {
      this.$emit("close");
    },
    exportCsv() {
      if(this.academia === null || this.academia === undefined || this.academia === {}) {
        Vue.toasted.error('Não escolheu uma academia de verão');
        return;
      }
      switch (this.exportType) {
        case "1":
          axios({url: `api/exportParticipantesAcademia/${this.academia}`, method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'academia_participantes.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Participantes exportados com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "2":
          axios({url: `api/exportAtividadesAcademia/${this.academia}`, method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'academia_atividades.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Atividades exportadas com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        default:
          Vue.toasted.error('Não escolheu os dados para exportar');
          break;
      }
    },
    getAcademias() {
      axios.get(`api/academias`).then(response => {
        this.academias = response.data.data;
      })
      .catch(response => {
        this.academias = [];
      })
    },
  }
};
</script>
