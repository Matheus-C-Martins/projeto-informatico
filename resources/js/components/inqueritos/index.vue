<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Inquéritos
        <v-spacer></v-spacer>
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Inquérito </button>
          </template>
          <criar-codigo @create="create" @close="close" :key="criarKey"></criar-codigo>
        </v-dialog>
     </v-card-title>
    </v-card>

    <v-divider style="margin-top: 0px"></v-divider>
    <v-data-table
      :headers="headers"
      :items="codigos"
      :items-per-page="10"
      :loading="loading"
      loading-text="A carregar inquéritos... Por favor aguarde"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalInqueritos"
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem inquéritos">
      <template v-slot:item.editar="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
      <template v-slot:item.inqueritos="{ item }">
        <v-icon small class="mr-2" @click="inqueritos(item)">{{ icons.mdiClipboardText }}</v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="700px">
      <editar-codigo @save="save" @close="close" :key="editarKey" :codigo="codigo"></editar-codigo>
    </v-dialog>
    <v-dialog v-model="dialogInqueritos" max-width="1100px">
      <codigo-inqueritos @close="close" :key="inqueritosKey" :codigo="codigo"></codigo-inqueritos>
    </v-dialog>
  </v-container>
</template>

<script>
import EditarCodigo from "./editar";
import CriarCodigo from "./criar";
import CodigoInqueritos from "./inqueritos";
import { mdiPencil, mdiClipboardText } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-codigo": EditarCodigo,
    "criar-codigo": CriarCodigo,
    "codigo-inqueritos": CodigoInqueritos,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      dialogInqueritos: false,
      editarKey: 0,
      criarKey: 0,
      inqueritosKey: 0,
      loading: true,
      totalInqueritos: 0,
      options: {},
      headers: [
        { text: 'Código', value: 'id', align: 'center', sortable: false },
        { text: 'Nome', value: 'nome', align: 'center', sortable: false },
        { text: 'Data',  value: 'data', align: 'center', sortable: false },
        { text: 'Hora',  value: 'hora', align: 'center', sortable: false },
        { text: 'Validade',  value: 'validade_string', align: 'center', sortable: false },
        { text: 'Respostas ao Inquérito', value: 'inqueritos', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
      ],
      codigos: [],
      editedIndex: -1,
      editedItem: {},
      codigo: {},
      icons: { mdiPencil, mdiClipboardText },
    };
  },
  methods: {
    initialize(){
      this.getCodigos();
      this.codigo={};
    },
    create(codigo) {
      axios.post("api/codigos", codigo).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.close();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    inqueritos(item) {
      this.editedIndex = this.codigos.indexOf(item);
      this.codigo = Object.assign({}, item);
      this.inqueritosKey+=1;
      this.dialogInqueritos = true;
    },
    edit(item) {
      this.editedIndex = this.codigos.indexOf(item);
      this.codigo = Object.assign({}, item);
      this.editarKey+=1;
      this.dialogEditar = true;
    },
    close() {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.dialogInqueritos = false;
      this.initialize();
    },
    save(codigo) {
      Object.assign(this.codigos[this.editedIndex], this.editedItem);
      axios.put(`api/codigos/${this.codigo.id}/update`, codigo).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.close()
    },
    getCodigos() {
      this.loading = true;
      axios.get(`api/codigos?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.codigos = response.data.data;
        this.totalInqueritos = response.data.meta.total;
      })
      .catch(response => {
        this.codigos = [];
        this.codigo = {};
      })
      .finally(() => this.loading = false);
    },
  },
  created() {
    this.$emit("linkTo", "/backoffice/inqueritos", 1);
  },
  mounted() {
    this.initialize();
  },
};
</script>
