<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Atividades
      <v-spacer></v-spacer>
        <v-dialog v-model="dialogCriar" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="criarKey+=1" class="btn btn-secondary block"> Criar Atividade </button>
          </template>
          <criar-atividade @create="create" @close="close" :key="criarKey" :atividade="atividade"></criar-atividade>
        </v-dialog>
      </v-card-title>
    </v-card>

    <v-divider style="margin-top: 0px"></v-divider>
      <v-card-subtitle class='display-6 font-weight-black pa-0'> Procurar </v-card-subtitle>
      <v-form>
        <v-row dense>
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
            <v-text-field label="Turma"
              v-model="turma"
              hide-details
              outlined
              dense
            ></v-text-field>
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
            <v-select label="Tipo de Atividade"
              v-model="tipo_atividade"
              :items="tipos"
              item-value="tipo"
              hide-details
              outlined
              dense
            ></v-select>
          </v-col>
          <v-col cols="12" sm="3">
            <v-menu
              ref="menuData"
              v-model="menuData"
              :close-on-content-click="false"
              :return-value.sync="data"
              transition="scale-transition"
              offset-y
              min-width="290px">
              <template v-slot:activator="{ on }">
                <v-text-field label="Data da Atividade"
                  v-model="data"
                  outlined
                  dense
                  readonly
                  v-on="on"
                  hide-details
                ></v-text-field>
              </template>
              <v-date-picker v-model="data" no-title type="month" @click:month="$refs.menuData.save(data)">
              </v-date-picker>
            </v-menu>
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
            <div v-if="expanded[0].numero_alunos !== defaultValue">
              <p class="font-weight-black"> Número de Alunos: <span class="font-weight-regular">{{expanded[0].numero_alunos}}</span></p>
            </div>
            <div v-if="expanded[0].descricao !== descriptionDefaultValue">
              <p class="font-weight-black"> Descrição: <span class="font-weight-regular">{{expanded[0].descricao}}</span></p>
            </div>
            <v-divider></v-divider>
            <h5 class="font-weight-black"> Contacto: </h5>
            <v-row>
              <v-col v-if="expanded[0].nome !== defaultValue" class="padTopBot">
                <p class="font-weight-black noBot"> Nome: <span class="font-weight-regular">{{expanded[0].nome}}</span></p>
              </v-col>
              <v-col v-if="expanded[0].email !== defaultValue" class="padTopBot">
                <p class="font-weight-black noBot"> Email: <span class="font-weight-regular">{{expanded[0].email}}</span></p>
              </v-col>
              <v-col v-if="expanded[0].telefone !== defaultValue" class="padTopBot">
                <p class="font-weight-black noBot"> Telefone: <span class="font-weight-regular">{{expanded[0].telefone}}</span></p>
              </v-col>
            </v-row>
          </div>
        </td>
      </template>
      <template v-slot:item.docentes="{ item }">
        <v-icon small class="mr-2" @click="docentes(item)"> {{ icons.mdiAccountTie }} </v-icon>
      </template>
      <template v-slot:item.editar="{ item }">
        <v-icon small class="mr-2" @click="edit(item)">{{ icons.mdiPencil }}</v-icon>
      </template>
      <template v-slot:item.remover="{ item }">
        <v-icon small class="mr-2" @click="deleteItem(item)"> {{ icons.mdiDelete }} </v-icon>
      </template>
      <template v-slot:item.workshop="{ item }">
        <button class="btn btn-secondary mr-2" @click="workshops(item)" v-if="item.tipo_atividade === 'Workshop'"> Workshops Associados </button>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogEditar" max-width="700px">
      <editar-atividade @save="save" @close="close" :key="editarKey" :atividade="atividade"></editar-atividade>
    </v-dialog>
    <v-dialog v-model="dialogWorkshop" max-width="900px">
      <workshop @close="close" :key="workshopKey" :atividade="atividade"></workshop>
    </v-dialog>
    <v-dialog v-model="dialogDocente" max-width="900px">
      <docente @close="close" :key="docenteKey" :atividade="atividade"></docente>
    </v-dialog>
  </v-container>
</template>

<script>
import EditarAtividade from "./editar";
import CriarAtividade from "./criar";
import Workshop from "./workshop";
import Docente from "./docente";
import { mdiPencil, mdiDelete, mdiAccountTie } from '@mdi/js';

export default {
  props: ["user"],
  components: {
    "editar-atividade": EditarAtividade,
    "criar-atividade": CriarAtividade,
    "workshop": Workshop,
    "docente": Docente,
  },
  data() {
    return {
      index: true,
      dialogEditar: false,
      dialogCriar: false,
      dialogWorkshop: false,
      dialogDocente: false,
      editarKey: 0,
      criarKey: 0,
      workshopKey: 0,
      docenteKey: 0,
      escolas: [],
      escola: {},
      turma: '',
      ano: '',
      tipo_atividade: '',
      tipos: [
        { tipo: "diaESTG", text: "Dia ESTG" },
        { tipo: "workshop", text: "Workshop" },
        { tipo: "seminario", text: "Seminário" },
        { tipo: "visita", text: "Visita à Escola" },
      ],
      dataInicio: '',
      dataFim:'',
      data:'',
      menuData: false,
      loading: true,
      totalAtividades: 0,
      options: {},
      expanded: [],
      singleExpand: true,
      defaultValue:'-----',
      descriptionDefaultValue:'',
      headers: [
        { text: 'Escola', value: 'escola', align: 'center', sortable: false, filterable: true},
        { text: 'Turma',  value: 'turma', align: 'center', sortable: false, filterable:true},
        { text: 'Ano', value: 'ano', align: 'center', sortable: false, filterable: true},
        { text: 'Data', value: 'data', align: 'center', sortable: false, filterable: true},
        { text: 'Hora', value: 'hora', align: 'center', sortable: false, filterable: true},
        { text: 'Duração', value: 'duracao', align: 'center', sortable: false, filterable: true},
        { text: 'Tipo de Atividade', value: 'tipo_atividade', align: 'center', sortable: false, filterable: true},
        { text: 'Detalhes', value: 'data-table-expand', align: 'center', sortable: false },
        { text: 'Docentes', value: 'docentes', align: 'center', sortable: false },
        { text: 'Editar', value: 'editar', align: 'center', sortable: false },
        { text: 'Remover', value: 'remover', align: 'center', sortable: false },
        { text: '', value: 'workshop', align: 'center', sortable: false },
      ],
      atividades: [],
      editedIndex: -1,
      editedItem: {},
      atividade: {},
      icons: { mdiPencil, mdiDelete, mdiAccountTie },
      trinta:['04', '06', '09', '11'],
    };
  },
  methods: {
    initialize() {
      this.getEscolas();
      this.getAtividades();
      this.atividade={};
    },
    create(atividade) {
      axios.post("api/atividades", atividade).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
        this.close();
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
    workshops(item) {
      this.editedIndex = this.atividades.indexOf(item);
      this.atividade = Object.assign({}, item);
      this.workshopKey += 1;
      this.dialogWorkshop = true;
    },
    docentes(item) {
      this.editedIndex = this.atividades.indexOf(item);
      this.atividade = Object.assign({}, item);
      this.docenteKey += 1;
      this.dialogDocente = true;
    },
    deleteItem (item) {
      confirm(`Tem a certeza que pertende eliminar a atividade do dia: ${item.data}?`) &&
      axios.delete(`/api/atividades/${item.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.loading = true;
        this.initialize();
      })
    },
    close () {
      this.dialogEditar = false;
      this.dialogCriar = false;
      this.dialogWorkshop = false;
      this.dialogDocente   = false;
      this.initialize();
    },
    save(atividade) {
      Object.assign(this.atividades[this.editedIndex], this.editedItem);
      axios.put(`api/atividades/${this.atividade.id}/update`, atividade).then(response => {
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
    getAtividades() {
      this.loading = true;
      axios.get(`api/atividades?escola=${(Object.keys(this.escola).length === 0 && this.escola.constructor === Object ? '' : this.escola)}&turma=${this.turma}&ano=${this.ano}&tipo_atividade=${this.tipo_atividade}&dataInicio=${this.dataInicio}&dataFim=${this.dataFim}&page=${this.options.page}&per_page=${this.options.itemsPerPage}`).then(response => {
        this.atividades = response.data.data;
        this.totalAtividades = response.data.meta.total;
      })
      .catch(response => {
        this.atividades = [];
        this.atividade = {};
      })
      .finally(() => this.loading = false);
    },
    getEscolas() {
      axios.get(`api/escolas`).then(response => {
        this.escolas = response.data.data;
      })
      .catch(response => {
        this.escolas = [];
      })
    },
  },
  created() {
    this.$emit("linkTo", "/backoffice/atividades", 1);
  },
  watch: {
    dialogEditar(val) {
      val || this.close()
    },
    dialogCriar(val) {
      val || this.close()
    },
    dialogWorkshop(val) {
      val || this.close()
    },
    dialogDocente(val) {
      val || this.close()
    },
    user(newVal, oldVal) {
      newVal;
    },
    expanded(newVal, oldVal) {
      if (newVal.length == 1){
        newVal;
      }
      [];
    },
    escola() {
      this.getAtividades();
    },
    turma() {
      this.getAtividades();
    },
    ano() {
      this.getAtividades();
    },
    tipo_atividade() {
      this.getAtividades();
    },
    data() {
      if (this.data !== ''){
        this.dataInicio = this.data+"-01";
        if (this.trinta.includes(this.data.substr(5, 7))) {
          this.dataFim = this.data+"-30";
        } else if(this.data.substr(5, 7) === '02') {
          this.dataFim = this.data+"-29";
        } else {
          this.dataFim = this.data+"-31";
        }
      }
      this.getAtividades();
    }
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