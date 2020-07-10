<template>
  <v-card>
    <v-card-title> Escolas Associadas 
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogAssociar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="associarKey+=1" class="btn btn-secondary block"> Associar Escola </button>
        </template>
        <associar-escola @associar="associar" @close="closeEfetuados" :key="associarKey" :contacto="contacto"></associar-escola>
      </v-dialog>
    </v-card-title>

    <v-card-text style="padding-bottom: 0px;">
      <v-data-table
        :headers="headers"
        :items="escolas"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar escolas associadas... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalEscolas"
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        show-expand
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem escolas associadas a este contacto">
        <template :elevation="0" v-slot:expanded-item="{ headers }">
          <td style="box-shadow:inset 0px 0px 0px 7px rgb(0, 0, 0)" :colspan="headers.length">
            <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
              <div v-if="expanded[0].descricao !== descriptionDefaultValue">
                <p class="font-weight-black"> Descrição: <span class="font-weight-regular">{{expanded[0].descricao}}</span></p>
              </div>
            </div>
          </td>
        </template>
        <template v-slot:item.efetuados="{ item }">
          <button class="btn btn-secondary mr-2" @click="efetuados(item)"> Contactos Efetuados </button>
        </template>
        <template v-slot:item.editar="{ item }">
          <v-icon small class="mr-2" @click="editar(item)">{{ icons.mdiPencil }}</v-icon>
        </template>
        <template v-slot:item.desassociar="{ item }">
          <v-icon small class="mr-2" @click="desassociar(item)"> {{ icons.mdiDelete }} </v-icon>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogEfetuados" max-width="700px">
        <contactos-efetuados @close="closeEfetuados" :key="efetuadosKey" :escola="escola"></contactos-efetuados>
      </v-dialog>
      <v-dialog v-model="dialogEditar" max-width="700px">
        <editar-associado @save="save" @close="closeEfetuados" :key="editarKey" :escola="escola" :contacto="contacto"></editar-associado>
      </v-dialog>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
import ContactosEfetuados from "./efetuados";
import AssociarEscola from "./associarEscola";
import EditarAssociado from "./editarAssociado";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["contacto"],
  components: {
    "contactos-efetuados": ContactosEfetuados,
    "associar-escola": AssociarEscola,
    "editar-associado": EditarAssociado,
  },
  data() {
    return {
      search: '',
      loading: true,
      totalEscolas: 0,
      dialogEfetuados: false,
      dialogAssociar: false,
      dialogEditar: false,
      efetuadosKey: 0,
      associarKey: 0,
      editarKey: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      descriptionDefaultValue:'',
      headers: [
        { text: 'Escola', value: 'escola', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo de Contacto',  value: 'tipo', align: 'center', sortable: false, filterable:true},
        { text: 'Descrição', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false, filterable: true},
        { text: 'Desassociar', value: 'desassociar', align: 'center', sortable: false },
        { text: '', value: 'efetuados', align: 'right', sortable: false}
      ],
      escolas: [],
      escola: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize(){
      this.getEscolas();
      this.escola={};
    },
    getEscolas() {
      this.loading = true;
      axios.get(`api/contactos/${this.contacto.id}?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.escolas = response.data.data;
        this.totalEscolas = response.data.meta.total;
      })
      .catch(response => {
        this.escolas = [];
        this.escola = {};
      })
      .finally(() => this.loading = false);
    },
    associar(escola) {
      axios.post("api/contactos/escolas", escola).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeEfetuados();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    save(associado) {
      axios.put(`api/contactos/escolas/${this.escola.id}/update`, associado).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.closeEfetuados();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    desassociar(item) {
      confirm(`Tem a certeza que pertende desassociar a escola: ${item.escola}?`) &&
      axios.delete(`/api/contactos/escolas/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true;
        this.initialize();
      })
    },
    editar(item) {
      this.editedIndex = this.escolas.indexOf(item);
      this.escola = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    efetuados(item) {
      this.editedIndex = this.escolas.indexOf(item);
      this.escola = Object.assign({}, item);
      this.efetuadosKey += 1;
      this.dialogEfetuados = true;
    },
    closeEfetuados() {
      this.dialogEfetuados = false;
      this.dialogAssociar = false;
      this.dialogEditar = false;
      this.initialize()
    },
    close() {
      this.$emit("close");
    },
  },
  watch: {
    dialogEfetuados(val) {
      val || this.closeEfetuados()
    },
    dialogAssociar(val) {
      val || this.closeEfetuados()
    },
    dialogEditar(val) {
      val || this.closeEfetuados()
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
