<template>
  <v-card>
    <v-card-title> Atividades Associadas 
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogAssociar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="associarKey+=1" class="btn btn-secondary block"> Associar Atividade </button>
        </template>
        <associar-atividade @associar="associar" @close="closeAtividade" :key="associarKey"></associar-atividade>
      </v-dialog>
      <span>&nbsp;</span>
      <button @click="exportar()" class="btn btn-secondary block"> Exportar Atividades </button>
    </v-card-title>

    <v-card-text style="padding-bottom: 0px;">
      <v-data-table
        :headers="headers"
        :items="atividades"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar atividades associadas... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalAtividades"
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        show-expand
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem atividades associadas a este docente">
        <template :elevation="0" v-slot:expanded-item="{ headers }">
          <td :colspan="headers.length">
            <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
              <div v-if="expanded[0].descricao_participacao !== descriptionDefaultValue">
                <p class="font-weight-regular">{{expanded[0].descricao_participacao}} </p>
              </div>
            </div>
          </td>
        </template>
        <template v-slot:item.action="{ item }">
          <v-icon small class="mr-2" @click="editar(item)">{{ icons.mdiPencil }}</v-icon>
          <span>| &nbsp;</span>
          <v-icon small class="mr-2" @click="desassociar(item)"> {{ icons.mdiDelete }} </v-icon>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogEditar" max-width="700px">
        <editar-atividade @save="save" @close="closeAtividade" :key="editarKey" :atividade="atividade"></editar-atividade>
      </v-dialog>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import EditarAtividade from "./editarAtividade";
import AssociarAtividade from "./associarAtividade";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["docente"],
  components: {
    "editar-atividade": EditarAtividade,
    "associar-atividade": AssociarAtividade,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogAssociar: false,
      editarKey: 0,
      associarKey: 0,
      loading: true,
      totalAtividades: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      descriptionDefaultValue:'',
      headers: [
        { text: 'Atividade', value: 'tipo_de_atividade', align: 'center', sortable: false},
        { text: 'Data', value: 'data', align: 'center', sortable: false},
        { text: 'Hora', value: 'hora', align: 'center', sortable: false},
        { text: 'Descrição', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Editar | Desassociar', value: 'action', align: 'center', sortable: false },
      ],
      atividades: [],
      editedIndex: -1,
      editedItem: {},
      atividade: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getAtividades();
      this.atividade={};
    },
    editar(item) {
      this.editedIndex = this.atividades.indexOf(item);
      this.atividade = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    desassociar(item) {
      confirm(`Tem a certeza que pertende desassociar a atividade: ${item.tipo_de_atividade}?`) &&
      axios.delete(`/api/docentes/${this.docente.id}/atividades/${item.id}`, {}).then(response => {
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
    closeAtividade() {
      this.dialogEditar = false;
      this.dialogAssociar = false;
      this.initialize();
    },
    associar(atividade) {
      axios.post(`api/docentes/${this.docente.id}/atividades`, atividade).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        axios.post('api/atividades/docente/email', this.docente).then(response => {
          Vue.toasted.show(response.data);
        })
        this.closeAtividade();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    save(atividade) {
      Object.assign(this.atividades[this.editedIndex], this.editedItem);
      axios.put(`api/docentes/${this.docente.id}/atividades/${atividade.id}/update`, atividade).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.closeAtividade()
    },
    getAtividades() {
      this.loading = true;
      axios.get(`api/docentes/${this.docente.id}/atividades?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.atividades = response.data.data;
        this.totalAtividades = response.data.meta.total;
      })
      .catch(response => {
        this.atividades = [];
        this.atividade = {};
      })
      .finally(() => this.loading = false);
    },
    exportar() {
      axios({url: `api/exportDocenteAtividades/${this.docente.id}`, method: 'GET', responseType: 'blob'}).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute('download', 'docente_atividades.csv');
        document.body.appendChild(fileLink);
        fileLink.click();
        Vue.toasted.show('Atividades exportadas com sucesso');
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    },
  },
  watch: {
    dialogEditar(val) {
      val || this.closeAtividade()
    },
    dialogAssociar(val) {
      val || this.closeAtividade()
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
