<template>
  <v-card>
    <v-card-title> Participantes Associados 
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogAssociar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="associarKey+=1" class="btn btn-secondary block"> Associar Participante </button>
        </template>
        <associar-participante @associar="associar" @close="closeParticipantes" :key="associarKey"></associar-participante>
      </v-dialog>
    </v-card-title>

    <v-card-text style="padding-bottom: 0px;">
      <v-data-table
        :headers="headers"
        :items="participantes"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar participantes associados... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalParticipantes"
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem participantes associados a esta atividade">
        <template v-slot:item.editar="{ item }">
          <v-icon small class="mr-2" @click="editar(item)">{{ icons.mdiPencil }}</v-icon>
        </template>
        <template v-slot:item.desassociar="{ item }">
          <v-icon small class="mr-2" @click="desassociar(item)"> {{ icons.mdiDelete }} </v-icon>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogEditar" max-width="700px">
        <editar-participante @save="save" @close="closeParticipantes" :key="editarKey" :participante="participante"></editar-participante>
      </v-dialog>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import EditarParticipante from "./editarAtividadeParticipante";
import AssociarParticipante from "./associarAtividadeParticipante";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["atividade"],
  components: {
    "editar-participante": EditarParticipante,
    "associar-participante": AssociarParticipante,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogAssociar: false,
      editarKey: 0,
      associarKey: 0,
      loading: true,
      totalParticipantes: 0,
      options: {},
      headers: [
        { text: 'Participante', value: 'participante', align: 'center', sortable: false},
        { text: 'Data', value: 'data', align: 'center', sortable: false },
        { text: 'Hora', value: 'hora', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Desassociar', value: 'desassociar', align: 'center', sortable: false },
      ],
      participantes: [],
      editedIndex: -1,
      editedItem: {},
      participante: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getParticipantes();
      this.participante={};
    },
    editar(item) {
      this.editedIndex = this.participantes.indexOf(item);
      this.participante = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    desassociar(item) {
      confirm(`Tem a certeza que pertende desassociar o participante: ${item.participante}?`) &&
      axios.delete(`/api/academias/atividades/${this.atividade.id}/participantes/${item.id}`, {}).then(response => {
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
    closeParticipantes() {
      this.dialogEditar = false;
      this.dialogAssociar = false;
      this.initialize();
    },
    associar(participante) {
      axios.post(`api/academias/atividades/${this.atividade.id}/participantes`, participante).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeParticipantes();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    save(participante) {
      Object.assign(this.participantes[this.editedIndex], this.editedItem);
      axios.put(`api/academias/atividades/${this.atividade.id}/participantes/${participante.id}/update`, participante).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.closeParticipantes()
    },
    getParticipantes() {
      this.loading = true;
      axios.get(`api/academias/atividades/${this.atividade.id}/participantes?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.participantes = response.data.data;
        this.totalParticipantes = response.data.meta.total;
      })
      .catch(response => {
        this.participantes = [];
        this.participante = {};
      })
      .finally(() => this.loading = false);
    },
  },
  watch: {
    dialogEditar (val) {
      val || this.closeParticipantes()
    },
    dialogAssociar (val) {
      val || this.closeParticipantes()
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
