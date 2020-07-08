<template>
  <v-card>
    <v-card-title> Respostas Ao Inquérito </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-data-table
        :headers="headers"
        :items="respostas"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar respostas ao inquérito... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalRespostas"
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        show-expand
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem respostas a este inquérito">
        <template :elevation="0" v-slot:expanded-item="{ headers }">
          <td style="box-shadow:inset 0px 0px 0px 7px rgb(0, 0, 0)" :colspan="headers.length">
            <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
              <div v-if="expanded[0].sugestao !== descriptionDefaultValue">
                <p class="font-weight-black"> Sugestões: <span class="font-weight-regular">{{expanded[0].sugestao}}</span></p>
              </div>
            </div>
          </td>
        </template>
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
  props: ["codigo"],
  data() {
    return {
      search: '',
      loading: true,
      totalRespostas: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      descriptionDefaultValue:'',
      headers: [
        { text: 'Data', value: 'data', align: 'center', sortable: false },
        { text: 'Hora', value: 'hora', align: 'center', sortable: false },
        { text: 'Resposta 1', value: 'resposta1', align: 'center', sortable: false },
        { text: 'Resposta 2', value: 'resposta2', align: 'center', sortable: false },
        { text: 'Resposta 3', value: 'resposta3', align: 'center', sortable: false },
        { text: 'Resposta 4', value: 'resposta4', align: 'center', sortable: false },
        { text: 'Resposta 5', value: 'resposta5', align: 'center', sortable: false },
        { text: 'Resposta 6', value: 'resposta6', align: 'center', sortable: false },
        { text: 'Sugestões', value: 'data-table-expand', align: 'center', sortable: false },
      ],
      respostas: [],
    };
  },
  mounted(){
    this.getRespostas();
  },
  methods: {
    getRespostas() {
      this.loading = true;
      axios.get(`api/codigos/${this.codigo.id}/inqueritos?page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.respostas = response.data.data;
        this.totalRespostas = response.data.meta.total;
      })
      .catch(response => {
        this.respostas = [];
      })
      .finally(() => this.loading = false);
    },
    close() {
      this.$emit("close");
    },
  }
};
</script>
