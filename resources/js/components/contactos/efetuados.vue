<template>
    <v-card>
      <v-card-title> Contactos Efetuados
        <v-spacer></v-spacer>
        <v-dialog v-model="dialogMarcar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="marcarKey+=1" class="btn btn-secondary block"> Marcar Como Efetuado </button>
          </template>
          <marcar-efetuado @marcar="marcar" @close="closeMarcar" :key="marcarKey"></marcar-efetuado>
        </v-dialog>
      </v-card-title>
      <v-card-text style="padding-bottom: 0px;">
        <v-data-table
          :headers="headers"
          :items="efetuados"
          :items-per-page="10"
          :loading="loading"
          loading-text="A carregar escoals associadas... Por favor aguarde"
          item-key="id"
          :options.sync="options"
          :server-items-length="totalEfetuados"
          :single-expand="singleExpand"
          :expanded.sync="expanded"
          show-expand
          :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
          class="elevation-1"
          no-data-text="Ainda não existem contactos efetuados associados a esta escola">
          <template :elevation="0" v-slot:expanded-item="{ headers }">
            <td :colspan="headers.length">
              <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
                <div v-if="expanded[0].descricao !== descriptionDefaultValue">
                  <p class="font-weight-regular">{{expanded[0].descricao}} </p>
                </div>
              </div>
            </td>
          </template>
          <template v-slot:item.action="{ item }">
            <v-icon small class="mr-2" @click="editar(item)">{{ icons.mdiPencil }}</v-icon>
            <span>| &nbsp;</span>
            <v-icon small class="mr-2" @click="desmarcar(item)"> {{ icons.mdiDelete }} </v-icon>
          </template>
        </v-data-table>
        <v-dialog v-model="dialogEditar" max-width="700px">
          <editar-efetuado @editar="save" @close="closeMarcar" :key="editarKey" :contacto="efetuado"></editar-efetuado>
        </v-dialog>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
      </v-card-actions>
    </v-card>
</template>

<script>
import MarcarEfetuado from "./marcarEfetuado";
import EditarEfetuado from "./editarEfetuado";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["escola"],
  components: {
    "marcar-efetuado": MarcarEfetuado,
    "editar-efetuado": EditarEfetuado,
  },
  data() {
    return {
      search: '',
      loading: true,
      totalEfetuados: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      descriptionDefaultValue:'',
      headers: [
        { text: 'Data', value: 'data', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo',  value: 'tipo', align: 'center', sortable: false, filterable:true},
        { text: 'Descrição', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Editar | Desmarcar',  value: 'action', align: 'center', sortable: false},
      ],
      efetuados: [],
      efetuado: {},
      dialogMarcar: false,
      dialogEditar: false,
      marcarKey: 0,
      editarKey: 0,
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getEfetuados();
      this.efetuado={};
    },
    getEfetuados() {
      this.loading = true;
      axios.get(`api/contactos/escolas/${this.escola.id}?page=${this.escola.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.efetuados = response.data.data;
        this.totalEfetuados = response.data.meta.total;
      })
      .catch(response => {
        this.efetuados = [];
        this.efetuado = {};
      })
      .finally(() => this.loading = false);
    },
    marcar(contacto) {
      axios.post(`api/contactos/escolas/${this.escola.id}`, contacto).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeMarcar();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    editar(item) {
      this.editedIndex = this.efetuados.indexOf(item);
      this.efetuado = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    save(contacto) {
      axios.put(`api/contactos/escolas/${this.escola.id}/${contacto.id}/update`, contacto).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeMarcar();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    desmarcar (item) {
      confirm(`Tem a certeza que pertende desmarcar este contacto?`) &&
      axios.delete(`api/contactos/escolas/${this.escola.id}/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true;
        this.initialize();
      })
    },
    closeMarcar() {
      this.dialogMarcar = false;
      this.dialogEditar = false;
      this.initialize()
    },
    close() {
      this.$emit("close");
    },
  },
  watch: {
    dialogMarcar (val) {
      val || this.closeMarcar()
    },
    dialogEditar (val) {
      val || this.closeMarcar()
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
