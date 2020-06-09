<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Docentes
        <v-spacer></v-spacer>
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Docente </button>
          </template>
          <criar-docente @create="create" @close="close" :key="criarKey" :docente="docente"></criar-docente>
        </v-dialog>
      </v-card-title>
    </v-card>

    <v-divider style="margin-top: 0px"></v-divider>
      <v-card-subtitle class='display-6 font-weight-black pa-0'> Procurar </v-card-subtitle>
      <v-form>
        <v-row dense>
          <v-col cols="12" sm="3">
            <v-text-field label="Nome"
              v-model="nome"
              hide-details
              outlined
              dense
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="3">
            <v-text-field label="Telefone Interno"
              v-model="telefone"
              hide-details
              outlined
              dense
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="3">
            <v-text-field label="Email"
              v-model="email"
              hide-details
              outlined
              dense
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="3">
            <v-text-field label="Telemóvel"
              v-model="telemovel"
              hide-details
              outlined
              dense
            ></v-text-field>
          </v-col>
        </v-row>
      </v-form>
    <v-divider></v-divider>

    <v-data-table
      :headers="headers"
      :items="docentes"
      :items-per-page="10"
      :loading="loading"
      loading-text="A carregar docentes... Por favor aguarde"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalDocentes"
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem docentes">
      <template v-slot:item.editar="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
      <template v-slot:item.remover="{ item }">
        <v-icon small @click="deleteItem(item)"> {{ icons.mdiDelete }} </v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="700px">
      <editar-docente @save="save" @close="close" :key="editarKey" :docente="docente"></editar-docente>
    </v-dialog>
  </v-container>
</template>

<script>
import EditarDocente from "./editar";
import CriarDocente from "./criar";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-docente": EditarDocente,
    "criar-docente": CriarDocente,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      editarKey: 0,
      criarKey: 0,
      nome: '',
      telefone: '',
      email: '',
      telemovel: '',
      loading: true,
      totalDocentes: 0,
      options: {},
      headers: [
        { text: 'Nome', value: 'nome', align: 'center', sortable: false, filterable: true},
        { text: 'Telefone Interno',  value: 'telefone_interno', align: 'center', sortable: false, filterable:true},
        { text: 'Telemóvel',  value: 'telemovel', align: 'center', sortable: false, filterable:true},
        { text: 'Email', value: 'email', align: 'center', sortable: false, filterable: true},
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
      ],
      docentes: [],
      editedIndex: -1,
      editedItem: {},
      docente: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getDocentes();
      this.docente={};
    },
    create(docente) {
      axios.post("api/docentes", docente).then(response => {
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
      this.editedIndex = this.docentes.indexOf(item)
      this.docente = Object.assign({}, item)
      this.editarKey+=1
      this.dialogEditar = true
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar o docente "${item.nome}"?`) &&
      axios.delete(`/api/docentes/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true
        this.initialize()
      })
    },
    close () {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.initialize();
    },
    save(docente) {
      Object.assign(this.docentes[this.editedIndex], this.editedItem);
      axios.put(`api/docentes/${this.docente.id}/update`, docente).then(response => {
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
    getDocentes() {
      this.loading = true;
      axios.get(`api/docentes?nome=${this.nome}&telefone=${this.telefone}&email=${this.email}&telemovel=${this.telemovel}&page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.docentes = response.data.data;
        this.totalDocentes = response.data.meta.total;
      })
      .catch(response => {
        this.docentes = [];
        this.docente = {};
      })
      .finally(() => this.loading = false);
    },
  },
  created(){
    this.$emit("linkTo", "/backoffice/docentes", 1);
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
    },
    nome() {
      this.getDocentes();
    },
    telefone() {
      this.getDocentes();
    },
    email() {
      this.getDocentes();
    },
    telemovel() {
      this.getDocentes();
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
