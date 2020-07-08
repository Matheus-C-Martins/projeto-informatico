<template>
  <v-card>
    <v-card-title>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogCriar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Participante </button>
        </template>
        <criar-participante @create="create" @close="closeParticipantes" :key="criarKey" :participante="participante"></criar-participante>
      </v-dialog>
    </v-card-title>
    <v-card-text>
        <v-card-subtitle class='display-6 font-weight-black pa-0'> Procurar </v-card-subtitle>
        <v-form>
          <v-row dense>
            <v-col cols="12" sm="3">
              <v-text-field label="Nome"
                v-model="nome"
                hide-details
                outlined
                dense
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="3">
              <v-select label="Escola"
                :items="escolas"
                item-value="id"
                item-text="nome"
                v-model="escola"
                hide-details
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" sm="2">
              <v-text-field label="Ano"
                v-model="ano"
                hide-details
                outlined
                dense
              ></v-text-field>
            </v-col> 
            <v-col cols="12" sm="2">
              <v-select label="Curso"
                v-model="curso"
                :items="cursos"
                item-value="tipo"
                hide-details
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" sm="2">
              <v-select label="Ano da Academia de Verão"
                :items="academias_de_verao"
                item-value="id"
                item-text="data"
                v-model="academia_de_verao"
                hide-details
                outlined
                dense
              ></v-select>
            </v-col>
          </v-row>
        </v-form>
      <v-divider></v-divider>
    
      <v-data-table
        :headers="headers"
        :items="participantes"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar participantes... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalParticipantes"
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        show-expand
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem participantes">
        <template :elevation="0" v-slot:expanded-item="{ headers }">
          <td style="box-shadow:inset 0px 0px 0px 7px rgb(0, 0, 0)" :colspan="headers.length">
            <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
              <div v-if="expanded[0].data_de_nascimento !== defaultValue">
                <p class="font-weight-black"> Data de Nascimento: <span class="font-weight-regular">{{expanded[0].data_de_nascimento}}</span></p>
              </div>
              <div v-if="expanded[0].contacto_do_aluno !== defaultValue">
                <p class="font-weight-black"> Contacto: <span class="font-weight-regular">{{expanded[0].contacto_do_aluno}}</span></p>
              </div>
              <v-divider></v-divider>
              <h5 class="font-weight-black"> Encarregado de Educação: </h5>
              <v-row>
                <v-col v-if="expanded[0].encarregado_de_educacao !== defaultValue" class="padTopBot">
                  <p class="font-weight-black noBot"><span class="font-weight-regular">{{expanded[0].encarregado_de_educacao}}</span></p>
                </v-col>
                <v-col v-if="expanded[0].contacto_do_ee !== defaultValue" class="padTopBot">
                  <p class="font-weight-black noBot"> Contacto: <span class="font-weight-regular">{{expanded[0].contacto_do_ee}}</span></p>
                </v-col>
              </v-row>
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
import EditarParticipante from "./editarParticipante";
import CriarParticipante from "./criarParticipante";
import { mdiPencil, mdiDelete } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-participante": EditarParticipante,
    "criar-participante": CriarParticipante,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      editarKey: 0,
      criarKey: 0,
      academias_de_verao: [],
      academia_de_verao: {},
      nome: '',
      escolas: [],
      escola: {},
      curso: '',
      cursos: [
        { tipo: "AV", text: "AV" },
        { tipo: "CT", text: "CT" },
        { tipo: "CSE", text: "CSE" },
        { tipo: "LH", text: "LH" },
        { tipo: "Profissional", text: "Profissional" },
      ],
      ano: '',
      loading: true,
      totalParticipantes: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      defaultValue:'-----',
      headers: [
        { text: 'Nome', value: 'nome', align: 'center', sortable: false, filterable: true },
        { text: 'Escola',  value: 'escola', align: 'center', sortable: false, filterable:true },
        { text: 'Ano',  value: 'ano', align: 'center', sortable: false, filterable:true },
        { text: 'Curso',  value: 'curso', align: 'center', sortable: false, filterable:true },
        { text: 'Região',  value: 'regiao', align: 'center', sortable: false, filterable:true },
        { text: 'Ano da Academia de Verão', value: 'academia_de_verao', align: 'center', sortable: false, filterable: true},
        { text: 'Detalhes', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
      ],
      participantes: [],
      editedIndex: -1,
      editedItem: {},
      participante: {},
      icons: { mdiPencil, mdiDelete },
    };
  },
  methods: {
    initialize() {
      this.getAcademias();
      this.getParticipantes();
      this.getEscolas();
      this.participante={};
    },
    create(participante) {
      axios.post("api/academias/participantes", participante).then(response => {
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
    edit(item) {
      this.editedIndex = this.participantes.indexOf(item);
      this.participante = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar o participante: ${item.nome}?`) &&
      axios.delete(`/api/academias/participantes/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true;
        this.initialize();
      })
    },
    closeParticipantes() {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.initialize();
    },
    close() {
      this.$emit("close");
    },
    save(participante) {
      Object.assign(this.participantes[this.editedIndex], this.editedItem);
      axios.put(`api/academias/participantes/${this.participante.id}/update`, participante).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.closeParticipantes();
    },
    getEscolas() {
      axios.get(`api/escolas`).then(response => {
        this.escolas = response.data.data;
      })
      .catch(response => {
        this.escolas = [];
      })
    },
    getAcademias() {
      axios.get(`api/academias`).then(response => {
        this.academias_de_verao = response.data.data;
      })
      .catch(response => {
        this.academias_de_verao = [];
      })
    },
    getParticipantes() {
      this.loading = true;
      axios.get(`api/academias/participantes?escola=${(Object.keys(this.escola).length === 0 && this.escola.constructor === Object ? '' : this.escola)}&academia_de_verao=${(Object.keys(this.academia_de_verao).length === 0 && this.academia_de_verao.constructor === Object ? '' : this.academia_de_verao)}&nome=${this.nome}&ano=${this.ano}&curso=${this.curso}&page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
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
    dialogCriar (val) {
      val || this.closeParticipantes()
    },
    expanded (newVal, oldVal) {
      if (newVal.length == 1){
        newVal;
      }
      [];
    },
    nome() {
      this.getParticipantes();
    },
    escola() {
      this.getParticipantes();
    },
    ano() {
      this.getParticipantes();
    },
    curso() {
      this.getParticipantes();
    },
    academia_de_verao() {
      this.getParticipantes();
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