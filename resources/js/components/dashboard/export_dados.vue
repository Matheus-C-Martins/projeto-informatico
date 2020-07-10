<template>
  <v-card>
    <v-card-title> Exportar Dados </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-row dense justify="center" align="center">
        <v-col>
          <v-select
            outlined
            :items="modelos"
            item-value="modelo"
            label="Modelo"
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
      modelos: [
        { modelo: "1", text: "Salas" },
        { modelo: "2", text: "Docentes" },
        { modelo: "3", text: "Contactos" },
        { modelo: "4", text: "Escolas" },
        { modelo: "5", text: "Workshops" },
        { modelo: "6", text: "Atividades" }
      ]
    };
  },
  methods: {
    close() {
      this.$emit("close");
    },
    exportCsv(){
      switch (this.exportType) {
        case "1":
          axios({url: 'api/exportSalas', method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'salas.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Salas exportadas com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "2":
          axios({url: 'api/exportDocentes', method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'docentes.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Docentes exportados com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "3":
          axios({url: 'api/exportContactos', method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'contactos.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Contactos exportados com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "4":
          axios({url: 'api/exportEscolas', method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'escolas.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Escolas exportadas com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "5":
          axios({url: 'api/exportWorkshops', method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'workshops.csv');
            document.body.appendChild(fileLink);
            fileLink.click();
            Vue.toasted.show('Workshops exportados com sucesso');
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "6":
          axios({url: 'api/exportAtividades', method: 'GET', responseType: 'blob'}).then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'atividades.csv');
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
    }
  }
};
</script>
