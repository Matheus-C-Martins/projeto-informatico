<template>
  <v-card>
    <v-card-title> Docentes Associados 
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogAssociar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="associarKey+=1" class="btn btn-secondary block"> Associar Docente </button>
        </template>
        <associar-docente @associar="associar" @close="closeDocentes" :key="associarKey"></associar-docente>
      </v-dialog>
    </v-card-title>

    <v-card-text style="padding-bottom: 0px;">
      <v-data-table
        :headers="headers"
        :items="docentes"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar docentes associados... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalDocentes"
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem docentes associados a esta atividade">
        <template v-slot:item.editar="{ item }">
          <v-icon small class="mr-2" @click="editar(item)">{{ icons.mdiPencil }}</v-icon>
        </template>
        <template v-slot:item.desassociar="{ item }">
          <v-icon small class="mr-2" @click="desassociar(item)"> {{ icons.mdiDelete }} </v-icon>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogEditar" max-width="700px">
        <editar-docente @save="save" @close="closeDocentes" :key="editarKey" :docente="docente"></editar-docente>
      </v-dialog>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import EditarDocente from "./editarDocente";
import AssociarDocente from "./associarDocente";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["atividade"],
  components: {
    "editar-docente": EditarDocente,
    "associar-docente": AssociarDocente,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogAssociar: false,
      editarKey: 0,
      associarKey: 0,
      loading: true,
      totalDocentes: 0,
      options: {},
      headers: [
        { text: 'Docente', value: 'docente', align: 'center', sortable: false},
        { text: 'Sala', value: 'sala', align: 'center', sortable: false},
        { text: 'Data', value: 'data', align: 'center', sortable: false },
        { text: 'Hora', value: 'hora', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Desassociar', value: 'desassociar', align: 'center', sortable: false },
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
    editar(item) {
      this.editedIndex = this.docentes.indexOf(item);
      this.docente = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    desassociar(item) {
      confirm(`Tem a certeza que pertende desassociar o docente: ${item.docente}?`) &&
      axios.delete(`/api/academias/atividades/${this.atividade.id}/docentes/${item.id}`, {}).then(response => {
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
    closeDocentes() {
      this.dialogEditar = false;
      this.dialogAssociar = false;
      this.initialize();
    },
    associar(docente) {
      axios.post(`api/academias/atividades/${this.atividade.id}/docentes`, docente).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeDocentes();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    save(docente) {
      Object.assign(this.docentes[this.editedIndex], this.editedItem);
      axios.put(`api/academias/atividades/${this.atividade.id}/docentes/${docente.id}/update`, docente).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.closeDocentes()
    },
    getDocentes() {
      this.loading = true;
      axios.get(`api/academias/atividades/${this.atividade.id}/docentes?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
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
  watch: {
    dialogEditar (val) {
      val || this.closeDocentes()
    },
    dialogAssociar (val) {
      val || this.closeDocentes()
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
