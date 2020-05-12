<template>
    <v-card>
      <v-card-title> Contactos Efetuados </v-card-title>
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
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem contactos efetuados associados a esta escola">
        </v-data-table>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <button class="btn btn-primary" @click.stop.prevent="close"> Fechar </button>
      </v-card-actions>
    </v-card>
</template>

<script>
export default {
  props: ["escola"],
  components: {
  },
  data() {
    return {
      search: '',
      loading: true,
      totalEfetuados: 0,
      options: {},
      headers: [
        { text: 'Data', value: 'data', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo',  value: 'tipo', align: 'center', sortable: false, filterable:true},
        { text: 'Descrição', value: 'descricao', align: 'center', sortable: false, filterable: true},
      ],
      efetuados: [],
      efetuado: {},
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
    close() {
      this.$emit("close");
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>
