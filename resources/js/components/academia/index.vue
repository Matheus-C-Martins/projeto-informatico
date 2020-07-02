<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Academia de Verão
        <v-spacer></v-spacer>
        <button @click="create()" class="btn btn-secondary block"> Criar Academia de Verão </button>
      </v-card-title>

      <v-divider style="margin-top: 0px"></v-divider>
      <v-card-text style="padding-bottom: 0px">
        <v-row dense align="center" justify="space-around">
          <div v-for="(box) in boxes" :key="box.id">
            <v-card tile min-width="155" class="mx-auto text-center caixa" color="#FFFFFF" dark>
              <v-card-text class="caixa-imagem">
                <v-avatar size="250" color="#000000" style="border-radius: 25%">
                  <span v-if="box.middle.participantesNum >= 0 && participantesNum!==undefined" class='white--text texto-size'> {{participantesNum}} </span>
                  <span v-else-if="box.middle.atividadesNum && atividadesNum!==undefined" class='white--text texto-size'> {{atividadesNum}} </span>
                  <v-img v-else aspect-ratio=1 :class="box.class" :src="box.img.url"></v-img>
                </v-avatar>
              </v-card-text>
              <v-card-actions style="background-color: #000000" class="justify-center">
                <v-dialog v-if="box.middle.participantesNum >= 0 && participantesNum!==undefined" v-model="dialogParticipantes" max-width="1300px">
                  <template class="container" v-slot:activator="{ on }">
                    <v-btn v-on="on" @click="participantesKey+=1" block text>{{box.action.text}}</v-btn>
                  </template>
                  <participantes :key="participantesKey" @close="close"></participantes>
                </v-dialog>

                <v-dialog v-else-if="box.middle.atividadesNum && atividadesNum!==undefined" v-model="dialogAtividades" max-width="1300px">
                  <template class="container" v-slot:activator="{ on }">
                    <v-btn v-on="on" @click="atividadesKey+=1" block text>{{box.action.text}}</v-btn>
                  </template>
                  <atividades :key="atividadesKey" @close="close"></atividades>
                </v-dialog>

                <v-dialog v-else v-model="dialogEstatisticas" max-width="1300px">
                  <template class="container" v-slot:activator="{ on }">
                    <v-btn v-on="on" @click="estatisticasKey+=1" block text>{{box.action.text}}</v-btn>
                  </template>
                  <estatisticas :key="estatisticasKey" @close="close"></estatisticas>
                </v-dialog>
              </v-card-actions>
            </v-card>
          </div>
        </v-row>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import Participantes from "./participantes";
import Atividades from "./atividades";
import Estatisticas from "./estatisticas";

export default {
  props: ['user'],
  components: {
    participantes: Participantes,
    atividades: Atividades,
    estatisticas: Estatisticas
  },
  data() {
    return {
      loading: 1,
      dialogParticipantes: false,
      dialogAtividades: false,
      dialogEstatisticas: false,
      participantesKey: 0,
      atividadesKey: 0,
      estatisticasKey: 0,
      boxes: [],
      atividadesNum: 0,
      participantesNum: 0,
    }
  },
  watch: {
    dialogParticipantes(val){
      val || this.close();
    },
    dialogAtividades(val){
      val || this.close();
    },
    dialogEstatisticas(val){
      val || this.close();
    },
  },
  methods: {
    getParticipantes() {
      axios.get(`api/academias/participantesNum`).then(response => {
        this.participantesNum = response.data;
      })
      .catch(response => {
        this.participantesNum = 0;
        Vue.toasted.error('Não foi possível encontrar o número de participantes');
      })
    },
    create() {
      var academia = {};
      academia.data = new Date().toJSON().slice(0,10);
      axios.post("api/academias", academia).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data[0]);
      })
      .catch(response => {
        console.log(response)
        Vue.toasted.error("Algo correu mal...");
      });
    },
    getAtividades() {
      axios.get(`api/academias/atividadesNum`).then(response => {
        this.atividadesNum = response.data;
      })
      .catch(response => {
        this.atividadesNum = 0;
        Vue.toasted.error('Não foi possível encontrar o número atividades');
      })
    },
    close() {
      this.dialogParticipantes = false;
      this.dialogAtividades = false;
      this.dialogEstatisticas = false;
    this.initialize();
    },
    initialize() {
      this.getParticipantes();
      this.getAtividades();
    }
  },
  mounted() {
    this.loading = 1;
  },
  created() {
    this.initialize();
    this.boxes = [{
        id: 0,
        class: 'head center',
        middle: {
          text: `Participantes:`,
          participantesNum: `${this.participantesNum}`,
        },
        action: {
          text: "Participantes",
          linkTo: "Participantes",
        }
      }, {
        id: 1,
        class: 'head center',
        middle: {
          text: `Atividades:`,
          atividadesNum: `${this.atividadesNum}`,
        },
        action: {
          text: "Atividades",
          linkTo: "Atividades",
        }
      }, {
        id: 2,
        class: 'head center',
        middle: {
          text: `Estatísticas:`,
        },
        img: {
          on: 1,
          url: "/storage/Logo_DEI.png"
        },
        action: {
          text: "Estatísticas",
          linkTo: "Estatisticas",
        }
      },
    ];
  
    this.$emit("linkTo", "/backoffice/academia", 0);
  }
}
</script>

<style scoped>
  .caixa {
    min-width:400px;
    margin-bottom:16px;
  }

  .caixa-imagem {
    min-height: 230px;
    text-align: -webkit-center;
  }

  .v-divider {
    margin-top: 4px;
    margin-bottom: 6px;
  }

  .head {
    width: 230px;
    height: 230px;
    text-align: -webkit-center;
  }

  .texto-size {
    font-size: 90px;
  }
</style>