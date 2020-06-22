<template>
  <v-card>
    <v-card-title> Workshops Associados 
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogAssociar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="associarKey+=1" class="btn btn-secondary block"> Associar Workshop </button>
        </template>
        <associar-workshop @associar="associar" @close="closeWorkshop" :key="associarKey"></associar-workshop>
      </v-dialog>
    </v-card-title>

    <v-card-text style="padding-bottom: 0px;">
      <v-data-table
        :headers="headers"
        :items="workshops"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar workshops associados... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalWorkshops"
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem workshops associados a esta atividade">
        <template v-slot:item.editar="{ item }">
          <v-icon small class="mr-2" @click="editar(item)">{{ icons.mdiPencil }}</v-icon>
        </template>
        <template v-slot:item.desassociar="{ item }">
          <v-icon small class="mr-2" @click="desassociar(item)"> {{ icons.mdiDelete }} </v-icon>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogEditar" max-width="700px">
        <editar-workshop @save="save" @close="closeWorkshop" :key="editarKey" :workshop="workshop"></editar-workshop>
      </v-dialog>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import EditarWorkshop from "./editarWorkshop";
import AssociarWorkshop from "./associarWorkshop";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["atividade"],
  components: {
    "editar-workshop": EditarWorkshop,
    "associar-workshop": AssociarWorkshop,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogAssociar: false,
      editarKey: 0,
      associarKey: 0,
      loading: true,
      totalWorkshops: 0,
      options: {},
      headers: [
        { text: 'Workshop', value: 'workshop', align: 'center', sortable: false},
        { text: 'Data', value: 'data', align: 'center', sortable: false },
        { text: 'Hora', value: 'hora', align: 'center', sortable: false },
        { text: 'Descrição', value: 'descricao', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Desassociar', value: 'desassociar', align: 'center', sortable: false },
      ],
      workshops: [],
      editedIndex: -1,
      editedItem: {},
      workshop: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getWorkshops();
      this.workshop={};
    },
    editar(item) {
      this.editedIndex = this.workshops.indexOf(item);
      this.workshop = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    desassociar(item) {
      confirm(`Tem a certeza que pertende desassociar o workshop: ${item.workshop}?`) &&
      axios.delete(`/api/atividades/${this.atividade.id}/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true;
        this.initialize();
      })
    },
    close() {
      this.$emit("close");
    },
    closeWorkshop() {
      this.dialogEditar = false;
      this.dialogAssociar = false;
      this.initialize();
    },
    associar(workshop) {
      axios.post(`api/atividades/${this.atividade.id}`, workshop).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeWorkshop();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    save(workshop) {
      Object.assign(this.workshops[this.editedIndex], this.editedItem);
      axios.put(`api/atividades/${this.atividade.id}/${workshop.id}/update`, workshop).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.closeWorkshop()
    },
    getWorkshops() {
      this.loading = true;
      axios.get(`api/atividades/${this.atividade.id}?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.workshops = response.data.data;
        this.totalWorkshops = response.data.meta.total;
      })
      .catch(response => {
        this.workshops = [];
        this.workshop = {};
      })
      .finally(() => this.loading = false);
    },
  },
  watch: {
    dialogEditar (val) {
      val || this.closeWorkshop()
    },
    dialogAssociar (val) {
      val || this.closeWorkshop()
    },
    user: function(newVal, oldVal) {
      newVal;
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
