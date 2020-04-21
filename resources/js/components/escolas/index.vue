<template>
  <v-container fluid>
    <v-card outlined>
      <v-container fluid class="pa-0">
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Escola </button>
          </template>
          <criar-escola @create="create" @close="close" :key="criarKey" :escola="escola"></criar-escola>
        </v-dialog>
      </v-container>
    </v-card>
    <br>
    <v-data-table
      :headers="headers"
      :items="escolas"
      :items-per-page="10"
      :loading="loading"
      loading-text="A carregar escolas... Por favor aguarde"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalEscolas"
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem escolas">
      <template v-slot:item.editar="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
      <template v-slot:item.remover="{ item }">
        <v-icon small @click="deleteItem(item)"> {{ icons.mdiDelete }} </v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="700px">
      <editar-escola @save="save" @close="close" :key="editarKey" :escola="escola"></editar-escola>
    </v-dialog>
  </v-container>
</template>

<script>
import EditarEscola from "./editar";
import CriarEscola from "./criar";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-escola": EditarEscola,
    "criar-escola": CriarEscola,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      editarKey: 0,
      criarKey: 0,
      search: '',
      loading: true,
      totalEscolas: 0,
      options: {},
      headers: [
        { text: 'Nome', value: 'nome', align: 'center', sortable: false, filterable: true},
        { text: 'Localização',  value: 'localizacao', align: 'center', sortable: false, filterable:true},
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
      ],
      escolas: [],
      editedIndex: -1,
      editedItem: {},
      escola: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nova Escola' : 'Editar Escola';
    },
  },
  methods: {
    initialize(){
      this.getEscolas();
      this.escola={};
    },
    create(escola) {
      axios.post("api/escolas", escola).then(response => {
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
    edit(item) {
      this.editedIndex = this.escolas.indexOf(item)
      this.escola = Object.assign({}, item)
      this.editarKey+=1
      this.dialogEditar = true
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar a escola "${item.nome}"?`) &&
      axios.delete(`/api/escolas/${item.id}`, {})
        .then(() => {
          this.loading = true
          this.initialize()
        })
    },
    close () {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.initialize();
    },
    save(escola) {
      Object.assign(this.escolas[this.editedIndex], this.editedItem);
      axios.put(`api/escolas/${this.escola.id}/update`, escola).then(response => {
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
    getEscolas() {
      this.loading = true;
      axios.get(`api/escolas?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.escolas = response.data.data;
        this.totalEscolas = response.data.meta.total;
      })
      .catch(response => {
        this.escolas = [];
        this.escola = {};
      })
      .finally(() => this.loading = false);
    },
  },
  created(){
    this.$emit("linkTo", "/backoffice/escolas", 1);
  },
  watch: {
    dialogEditar (val) {
      val || this.close()
    },
    dialogCriar (val) {
      val || this.close()
    },
    user: function(newVal, oldVal) {
      newVal;
    }
  },
  mounted(){
    this.initialize();
  },
};
</script>
