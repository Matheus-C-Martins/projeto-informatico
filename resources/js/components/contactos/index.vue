<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Contactos
      <v-spacer></v-spacer>
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Contacto </button>
          </template>
          <criar-contacto @create="create" @close="close" :key="criarKey" :contacto="contacto"></criar-contacto>
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
            <v-text-field label="Telefone"
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
            <v-select label="Sexo"
              v-model="sexo"
              :items="sexos"
              item-value="sexo"
              hide-details
              outlined
              dense
            ></v-select>
          </v-col>
        </v-row>
      </v-form>
    <v-divider></v-divider>
    
    <v-data-table
      :headers="headers"
      :items="contactos"
      :items-per-page="10"
      :loading="loading"
      loading-text="A carregar contactos... Por favor aguarde"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalContactos"
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem contactos">
      <template v-slot:item.editar="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
      <template v-slot:item.remover="{ item }">
        <v-icon small class="mr-2" @click="deleteItem(item)"> {{ icons.mdiDelete }} </v-icon>
      </template>
      <template v-slot:item.escolas="{ item }">
        <v-icon small class="mr-2" @click="escolas(item)">{{ icons.mdiSchool }}</v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="700px">
      <editar-contacto @save="save" @close="close" :key="editarKey" :contacto="contacto"></editar-contacto>
    </v-dialog>
    <v-dialog v-model="dialogEscolas" max-width="1000px">
      <contacto-escolas @close="close" :key="escolasKey" :contacto="contacto"></contacto-escolas>
    </v-dialog>
  </v-container>
</template>

<script>
import EditarContacto from "./editar";
import CriarContacto from "./criar";
import ContactoEscolas from "./escolas";
import { mdiPencil, mdiDelete, mdiSchool } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-contacto": EditarContacto,
    "criar-contacto": CriarContacto,
    "contacto-escolas": ContactoEscolas,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      dialogEscolas: false,
      editarKey: 0,
      criarKey: 0,
      escolasKey: 0,
      nome: '',
      telefone: '',
      email: '',
      sexo: '',
      sexos: [
        { sexo: "masculino", text: "Masculino" },
        { sexo: "feminino", text: "Feminino" },
        { sexo: "outro", text: "Outro" },
      ],
      loading: true,
      totalContactos: 0,
      options: {},
      headers: [
        { text: 'Nome', value: 'nome', align: 'center', sortable: false, filterable: true},
        { text: 'Telefone',  value: 'telefone', align: 'center', sortable: false, filterable:true},
        { text: 'Email', value: 'email', align: 'center', sortable: false, filterable: true},
        { text: 'Sexo', value: 'sexo', align: 'center', sortable: false, filterable: true},
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
        { text: 'Escolas', value: 'escolas', align: 'center', sortable: false },
      ],
      contactos: [],
      editedIndex: -1,
      editedItem: {},
      contacto: {},
      icons: { mdiPencil, mdiDelete, mdiSchool },
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
      this.editedIndex = this.contactos.indexOf(item);
      this.contacto = Object.assign({}, item);
      this.editarKey+=1;
      this.dialogEditar = true;
    },
    escolas(item) {
      this.editedIndex = this.contactos.indexOf(item);
      this.contacto = Object.assign({}, item);
      this.escolasKey+=1;
      this.dialogEscolas = true;
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar o contacto de: ${item.nome}?`) &&
      axios.delete(`/api/contactos/${item.id}`, {})
        .then(() => {
          this.loading = true;
          this.initialize();
        })
    },
    close () {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.dialogEscolas = false;
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
      axios.get(`api/contactos?nome=${this.nome}&telefone=${this.telefone}&email=${this.email}&sexo=${this.sexo}&page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.contactos = response.data.data;
        this.totalContactos = response.data.meta.total;
      })
      .catch(response => {
        this.contactos = [];
        this.contacto = {};
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
    dialogEscolas (val) {
      val || this.close()
    },
    user: function(newVal, oldVal) {
      newVal;
    },
    nome() {
      this.getContactos();
    },
    telefone() {
      this.getContactos();
    },
    email() {
      this.getContactos();
    },
    sexo() {
      this.getContactos();
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
