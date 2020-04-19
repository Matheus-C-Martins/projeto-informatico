<template>
  <v-container fluid>
    <v-card outlined>
      <v-container fluid class="pa-0">
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Contacto </button>
          </template>
          <criar-contacto @create="create" @close="close" :key="criarKey" :contacto="contacto"></criar-contacto>
        </v-dialog>
      </v-container>
    </v-card>
    <br>
    <v-data-table
      :headers="headers"
      :items="contactos"
      :items-per-page="10"
      :loading="loading"
      loading-text="Loading... Please wait"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalContactos"
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem contactos">
      <template v-slot:item.action="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="700px">
      <editar-contacto @save="save" @close="close" :key="editarKey" :contacto="contacto"></editar-contacto>
    </v-dialog>
    </v-row>
  </v-container>
</template>

<script>
import EditarContacto from "./editar";
import CriarContacto from "./criar";
import { mdiPencil } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-contacto": EditarContacto,
    "criar-contacto": CriarContacto,
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
      totalContactos: 0,
      options: {},
      headers: [
        { text: 'ID', value: 'id', align: 'center', sortable: false, filterable: true},
        { text: 'Nome', value: 'nome', align: 'center', sortable: false, filterable: true},
        { text: 'Telefone',  value: 'telefone', align: 'center', sortable: false, filterable:true},
        { text: 'Email', value: 'email', align: 'center', sortable: false, filterable: true},
        { text: 'Sexo', value: 'sexo', align: 'center', sortable: false, filterable: true},
        { text: 'Editar', value: 'action', align: 'center', sortable: false },
      ],
      contactos: [],
      editedIndex: -1,
      editedItem: {},
      contacto: {},
      icons: { mdiPencil },
    };
  },
  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Novo Contacto' : 'Editar Contacto';
    },
  },
  methods: {
    initialize(){
      this.getContactos();
      this.contacto={};
    },
    create(contacto) {
      axios.post("api/contactos", contacto).then(response => {
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
      this.editedIndex = this.contactos.indexOf(item)
      this.contacto = Object.assign({}, item)
      this.editarKey+=1
      this.dialogEditar = true
    },
    close () {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.initialize();
    },
    save(contacto) {
      Object.assign(this.contactos[this.editedIndex], this.editedItem);
      axios.put(`api/contactos/${this.contacto.id}/update`, contacto).then(response => {
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
    getContactos() {
      this.loading = true;
      axios.get(`api/contactos?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.contactos = response.data.data;
        this.totalContactos = response.data.meta.total;
      })
      .finally(() => this.loading = false);
    },
  },
  created(){
    this.$emit("linkTo", "/backoffice/contactos", 1);
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
