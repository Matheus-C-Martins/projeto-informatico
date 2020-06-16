<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Cursos
      <v-spacer></v-spacer>
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Curso </button>
          </template>
          <criar-curso @create="create" @close="close" :key="criarKey"></criar-curso>
        </v-dialog>
      </v-card-title>
    </v-card>

    <v-divider style="margin-top: 0px"></v-divider>
      <v-card-subtitle class='display-6 font-weight-black pa-0'> Procurar </v-card-subtitle>
    <v-divider></v-divider>
    
    <v-data-table
      :headers="headers"
      :items="cursos"
      :items-per-page="10"
      :loading="loading"
      loading-text="A carregar cursos... Por favor aguarde"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalCursos"
      :single-expand="singleExpand"
      :expanded.sync="expanded"
      show-expand
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem cursos">
      <template :elevation="0" v-slot:expanded-item="{ headers }">
        <td style="box-shadow:inset 0px 0px 0px 7px rgb(0, 0, 0)" :colspan="headers.length">
          <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
            <div v-if="expanded[0].objetivos !== defaultValue">
              <p class="font-weight-black"> Objetivos: <span class="font-weight-regular">{{expanded[0].objetivos}}</span></p>
            </div>        
          </div>
        </td>
      </template>
      <template v-slot:item.editar="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
      <template v-slot:item.remover="{ item }">
        <v-icon small class="mr-2" @click="deleteItem(item)"> {{ icons.mdiDelete }} </v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="800px">
      <editar-curso @save="save" @close="close" :key="editarKey" :curso="curso"></editar-curso>
    </v-dialog>
  </v-container>
</template>

<script>
import EditarCurso from "./editar";
import CriarCurso from "./criar";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-curso": EditarCurso,
    "criar-curso": CriarCurso,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      editarKey: 0,
      criarKey: 0,
      loading: true,
      totalCursos: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      defaultValue:'',
      headers: [
        { text: 'Abreviatura', value: 'abreviatura', align: 'center', sortable: false, filterable: true},
        { text: 'Nome',  value: 'nome', align: 'center', sortable: false, filterable:true},
        { text: 'Semestres', value: 'semestres', align: 'center', sortable: false, filterable: true},
        { text: 'ECTS', value: 'ECTS', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo', value: 'tipo', align: 'center', sortable: false, filterable: true},
        { text: 'Vagas', value: 'vagas', align: 'center', sortable: false, filterable: true},
        { text: 'Contato', value: 'contato', align: 'center', sortable: false, filterable: true},
        { text: 'Detalhes', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
      ],
      cursos: [],
      editedIndex: -1,
      editedItem: {},
      curso: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getCursos();
      this.curso={};
    },
    create(curso) {
      axios.post("api/cursos", curso).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show("Curso criado com sucesso");
        this.close();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    edit(item) {
      this.editedIndex = this.cursos.indexOf(item);
      this.curso = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar o curso: ${item.nome}?`) &&
      axios.delete(`/api/cursos/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data.data);
          return;
        }
        Vue.toasted.show("Curso eliminado com sucesso");
        this.loading = true;
        this.initialize();
      })
    },
    close () {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.initialize();
    },
    save(curso) {
      Object.assign(this.cursos[this.editedIndex], this.editedItem);
      axios.put(`api/cursos/${this.curso.id}/update`, curso).then(response => {
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
    getCursos() {
      this.loading = true;
      axios.get(`api/cursosWeb?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.cursos = response.data.data;
        this.totalCursos = response.data.meta.total;
      })
      .catch(response => {
        this.cursos = [];
        this.curso = {};
      })
      .finally(() => this.loading = false);
    },
  },
  created(){
    this.$emit("linkTo", "/backoffice/cursos", 1);
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
    expanded (newVal, oldVal) {
      if (newVal.length == 1){
        newVal;
      }
      [];
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>

<style scoped>
.padTopBot {
  padding-top: 0px;
  padding-bottom: 0px;
}

.noBot {
  margin-bottom: 0px;
}
</style>