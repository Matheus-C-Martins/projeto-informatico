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
      loading-text="A carregar escoals associadas... Por favor aguarde"
      item-key="id"
      :options.sync="options"
      :server-items-length="totalEscolas"
      :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
      class="elevation-1"
      no-data-text="Ainda não existem escolas associadas a este contacto">
      <template v-slot:item.efetuados="{ item }">
        <button class="btn btn-secondary mr-2" @click="efetuados(item)"> Contactos Efetuados </button>
      </template>
      </v-data-table>
      <v-dialog v-model="dialogEfetuados" max-width="700px">
        <contactos-efetuados @close="closeEfetuados" :key="efetuadosKey" :escola="escola"></contactos-efetuados>
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

export default {
  props: ["contacto"],
  components: {
    "contactos-efetuados": ContactosEfetuados,
    "associar-escola": AssociarEscola,
  },
  data() {
    return {
      search: '',
      loading: true,
      totalEscolas: 0,
      dialogEfetuados: false,
      dialogAssociar: false,
      efetuadosKey: 0,
      associarKey: 0,
      options: {},
      headers: [
        { text: 'Escola', value: 'escola', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo de Contacto',  value: 'tipo', align: 'center', sortable: false, filterable:true},
        { text: 'Descrição', value: 'descricao', align: 'center', sortable: false, filterable: true},
        { text: '', value: 'efetuados', align: 'right', sortable: false}
      ],
      escolas: [],
      escola: {},
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
    efetuados(item) {
      this.editedIndex = this.escolas.indexOf(item);
      this.escola = Object.assign({}, item);
      this.efetuadosKey += 1;
      this.dialogEfetuados = true;
    },
    closeEfetuados() {
      this.dialogEfetuados = false;
      this.dialogAssociar = false;
      this.initialize()
    },
    close() {
      this.$emit("close");
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
