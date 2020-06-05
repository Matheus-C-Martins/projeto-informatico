<template>
  <v-card>
    <v-card-title> Importar Dados </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-row dense justify="center" align="center">
        <v-col>
            <v-select
              outlined
              :items="modelos"
              item-value="modelo"
              label="Modelo"
              hide-details
              v-model="importType"
              dense
            ></v-select>
          </v-col>
        <v-col>
          <v-text-field label="Ficheiro CSV"
            v-model="fileName"
            @click="pickCSV"
            hide-details
            readonly
            outlined
            dense>
          </v-text-field>
          <input
            type="file"
            style="display: none"
            ref="file"
            accept=".csv"
            @change="fileChanged">
        </v-col>
        <v-col md="auto" >
          <button @click.stop.prevent="importCsv" class="btn btn-secondary block"> Importar </button>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data: function() {
    return {
      fileName: '',
      file: '',
      csv: '',
      importType: 0,
      modelos: [
        { modelo: "1", text: "Salas" },
        { modelo: "2", text: "Docentes" },
        { modelo: "3", text: "Contactos" },
        { modelo: "4", text: "Escolas" },
        { modelo: "5", text: "Workshops" }
      ]
    };
  },
  methods: {
    close() {
      this.$emit("close");
    },
    importCsv(){
      if(this.csv == '' || this.csv == undefined || this.csv == null) {
        Vue.toasted.error('Tem de inserir um ficheiro');
        return;
      }
      let formData = new FormData();
      formData.append('csv_file', this.csv);
      switch (this.importType) {
        case "1":
          this.modelo = 0;
          axios.post("api/importSalas", formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            if(response.status!=200) {
              Vue.toasted.error(response.data);
              return;
            }
            Vue.toasted.show(response.data);
            this.close();
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "2":
          this.modelo = 0;
          axios.post("api/importDocentes", formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            if(response.status!=200) {
              Vue.toasted.error(response.data);
              return;
            }
            Vue.toasted.show(response.data);
            this.close();
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "3":
          this.modelo = 0;
          axios.post("api/importContactos", formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            if(response.status!=200) {
              Vue.toasted.error(response.data);
              return;
            }
            Vue.toasted.show(response.data);
            this.modelo = 0;
            this.close();
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "4":
          this.modelo = 0;
          axios.post("api/importEscolas", formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            if(response.status!=200) {
              Vue.toasted.error(response.data);
              return;
            }
            Vue.toasted.show(response.data);
            this.close();
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        case "5":
          this.modelo = 0;
          axios.post("api/importWorkshops", formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            if(response.status!=200) {
              Vue.toasted.error(response.data);
              return;
            }
            Vue.toasted.show(response.data);
            this.close();
          })
          .catch(response => {
            Vue.toasted.error('Algo correu mal...');
          });
          break;
        default:
          this.modelo = 0;
          Vue.toasted.error('Formatação do ficheiro incorreta');
          break;
      }
    },
    pickCSV () {
      this.$refs.file.click();
    },
    fileChanged (e) {
      const files = e.target.files
      if(files[0] !== undefined) {
        this.fileName = files[0].name
        this.csv = this.$refs.file.files[0]
      } else {
        this.fileName = '';
        this.file = '';
        this.csv = '';
      }
    },
  }
};
</script>
