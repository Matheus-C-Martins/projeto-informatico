<template>
  <v-card>
    <v-card-title> Atividades
      <v-spacer></v-spacer>
      <v-dialog v-model="dialogCriar" max-width="700px">
        <template class="container" v-slot:activator="{ on }">
          <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Atividade </button>
        </template>
        <criar-atividade @create="create" @close="closeAtividades" :key="criarKey" :atividade="atividade"></criar-atividade>
      </v-dialog>
    </v-card-title>
    <v-card-text>
        <v-card-subtitle class='display-6 font-weight-black pa-0'> Procurar </v-card-subtitle>
        <v-form>
          <v-row dense>
            <v-col>
              <v-text-field label="Nome"
                v-model="nome"
                hide-details
                outlined
                dense
              ></v-text-field>
            </v-col>
            <v-col>
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
            <v-col>
              <v-select label="Tipo de Atividade"
                v-model="tipo_atividade"
                :items="tipos"
                item-value="tipo"
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
        :items="atividades"
        :items-per-page="10"
        :loading="loading"
        loading-text="A carregar atividades... Por favor aguarde"
        item-key="id"
        :options.sync="options"
        :server-items-length="totalAtividades"
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        show-expand
        :footer-props="{ itemsPerPageOptions: [5, 10, 20, 50] }"
        class="elevation-1"
        no-data-text="Ainda não existem atividades">
        <template :elevation="0" v-slot:expanded-item="{ headers }">
          <td style="box-shadow:inset 0px 0px 0px 7px rgb(0, 0, 0)" :colspan="headers.length">
            <div v-if="expanded.length" style="margin-top:15px; margin-bottom:15px">
              <div v-if="expanded[0].observacoes !== descriptionDefaultValue">
                <p class="font-weight-black"> Observações: <span class="font-weight-regular">{{expanded[0].observacoes}}</span></p>
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
        <template v-slot:item.action="{ item }">
          <v-icon small class="mr-2" @click="participantes(item)"> {{ icons.mdiAccount }} </v-icon>
          <v-icon small class="mr-2" @click="docentes(item)"> {{ icons.mdiAccountTie }} </v-icon>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogEditar" max-width="700px">
        <editar-atividade @save="save" @close="closeAtividades" :key="editarKey" :atividade="atividade"></editar-atividade>
      </v-dialog>
      <v-dialog v-model="dialogPaticipantes" max-width="700px">
        <participantes @close="closeAtividades" :key="participantesKey" :atividade="atividade"></participantes>
      </v-dialog>
      <v-dialog v-model="dialogDocentes" max-width="700px">
        <docentes @close="closeAtividades" :key="docentesKey" :atividade="atividade"></docentes>
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
import CriarAtividade from "./criarAtividade";
import Participantes from "./atividadeParticipantes";
import Docentes from "./atividadeDocentes";
import { mdiPencil, mdiDelete, mdiAccount, mdiAccountTie } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-atividade": EditarAtividade,
    "criar-atividade": CriarAtividade,
    "participantes": Participantes,
    "docentes": Docentes,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      dialogPaticipantes: false,
      dialogDocentes: false,
      editarKey: 0,
      criarKey: 0,
      participantesKey: 0,
      docentesKey: 0,
      academias_de_verao: [],
      academia_de_verao: {},
      nome: '',
      tipo_atividade: '',
      tipos: [
        { tipo: "aula", text: "Aula" },
        { tipo: "workshop", text: "Workshop" },
      ],
      loading: true,
      totalAtividades: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      defaultValue:'-----',
      descriptionDefaultValue:'',
      headers: [
        { text: 'Nome', value: 'nome', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo',  value: 'tipo', align: 'center', sortable: false, filterable:true},
        { text: 'Ano da Academia de Verão', value: 'academia_de_verao', align: 'center', sortable: false, filterable: true},
        { text: 'Detalhes', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
        { text: 'Participantes/Docentes', value: 'action', align: 'center', sortable: false },
      ],
      atividades: [],
      editedIndex: -1,
      editedItem: {},
      atividade: {},
      icons: { mdiPencil, mdiDelete, mdiAccount, mdiAccountTie },
    };
  },
  methods: {
    initialize() {
      this.getAcademias();
      this.getAtividades();
      this.atividade={};
    },
    create(atividade) {
      axios.post("api/academias/atividades", atividade).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.closeAtividades();
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
    },
    edit(item) {
      this.editedIndex = this.atividades.indexOf(item);
      this.atividade = Object.assign({}, item);
      this.editarKey += 1;
      this.dialogEditar = true;
    },
    participantes(item) {
      this.editedIndex = this.atividades.indexOf(item);
      this.atividade = Object.assign({}, item);
      this.participantesKey += 1;
      this.dialogPaticipantes = true;
    },
    docentes(item) {
      this.editedIndex = this.atividades.indexOf(item);
      this.atividade = Object.assign({}, item);
      this.docentesKey += 1;
      this.dialogDocentes = true;
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar a atividade: ${item.nome}?`) &&
      axios.delete(`/api/academias/atividades/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true;
        this.initialize();
      })
    },
    closeAtividades() {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.dialogPaticipantes = false;
      this.dialogDocentes = false;
      this.initialize();
    },
    close() {
      this.$emit("close");
    },
    save(atividade) {
      Object.assign(this.atividades[this.editedIndex], this.editedItem);
      axios.put(`api/academias/atividades/${this.atividade.id}/update`, atividade).then(response => {
        if(response.status == 200){
          Vue.toasted.show(response.data);
        } else {
          Vue.toasted.error(response.data)
        }
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal...");
      });
      this.closeAtividades();
    },
    getAcademias() {
      axios.get(`api/academias`).then(response => {
        this.academias_de_verao = response.data.data;
      })
      .catch(response => {
        this.academias_de_verao = [];
      })
    },
    getAtividades() {
      this.loading = true;
      axios.get(`api/academias/atividades?academia_de_verao=${(Object.keys(this.academia_de_verao).length === 0 && this.academia_de_verao.constructor === Object ? '' : this.academia_de_verao)}&nome=${this.nome}&tipo=${this.tipo_atividade}&page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.atividades = response.data.data;
        this.totalAtividades = response.data.meta.total;
      })
      .catch(response => {
        this.atividades = [];
        this.atividade = {};
      })
      .finally(() => this.loading = false);
    },
  },
  watch: {
    dialogEditar (val) {
      val || this.closeAtividades()
    },
    dialogCriar (val) {
      val || this.closeAtividades()
    },
    dialogPaticipantes (val) {
      val || this.closeAtividades()
    },
    dialogDocentes (val) {
      val || this.closeAtividades()
    },
    expanded (newVal, oldVal) {
      if (newVal.length == 1){
        newVal;
      }
      [];
    },
    nome() {
      this.getAtividades();
    },
    academia_de_verao() {
      this.getAtividades();
    },
    tipo_atividade() {
      this.getAtividades();
    },
  },
  mounted(){
    this.initialize();
  },
};
</script>