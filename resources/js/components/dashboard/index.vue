<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Dashboard
        <v-spacer></v-spacer>
        <v-dialog v-if="user.tipo == 'a'" v-model="dialog" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="createUserKey+=1" class="btn btn-secondary block"> Criar Utilizador </button>
          </template>
          <create-user :newUser="newUser" :key="createUserKey" @create="createUser" @close="close"></create-user>
        </v-dialog>
        <span>&nbsp;</span>
        <v-dialog v-if="user.tipo == 'a'" v-model="dialogRemoveUser" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="removeUserKey+=1" class="btn btn-secondary block"> Eliminar Utilizador </button>
          </template>
          <remove-user :newUser="newUser" :key="removeUserKey" @remove="removeUser" @close="close"></remove-user>
        </v-dialog>
      </v-card-title>
      <v-divider style="margin-top: 0px"></v-divider>
      <v-card-text style="padding-bottom: 0px">
        <v-row dense align="center" justify="space-around">
          <div v-for="(box) in boxes" :key="box.id">
            <v-card tile min-width="155" class="mx-auto text-center caixa" color="#FFFFFF" dark>
              <v-card-text class="caixa-imagem">
                <v-img style="border-radius: 25%" aspect-ratio=1 :class="box.class" :src="box.img.url"></v-img>
              </v-card-text>
              <v-card-text style="color:black" class="caixa-texto">
                <p style="margin-bottom: 0px;"> {{box.middle.text}} </p>
                <v-divider light></v-divider>
                <p v-if="box.middle.atividadesRestantes >= 0 && atividadesRestantes!==undefined" class='display-1 font-weight-black'>{{atividadesRestantes}}</p>
                <p v-else class='display-1 font-weight-black'>{{box.middle.template}}</p>
              </v-card-text>
              <v-card-actions style="background-color: #000000" class="justify-center">
                <v-btn @click.stop.prevent="linkTo(box.action.linkTo, box.action.routeId)" block text>{{box.action.text}}</v-btn>
              </v-card-actions>
            </v-card>
          </div>
        </v-row>
      </v-card-text>
      <v-card-actions style="padding-top:0px">
        <v-card flat tile width="100%" class="text-center pa-0">
          <v-card-text class="pa-0">
            <v-dialog v-model="dialogAdiconarSala" max-width="700px">
              <template class="container" v-slot:activator="{ on }">
                <button v-on="on" @click="addSalaKey+=1" class="btn btn-secondary block"> Adicionar Sala </button>
              </template>
              <add-sala :key="addSalaKey" @close="close" @create="createSala"></add-sala>
            </v-dialog>
            <span>&nbsp;</span>
            <v-dialog v-model="dialogRemoverSala" max-width="700px">
              <template class="container" v-slot:activator="{ on }">
                <button v-on="on" @click="removeSalaKey+=1" class="btn btn-secondary block"> Remover Sala </button>
              </template>
              <remove-sala :key="removeSalaKey" @close="close" @remove="removeSala"></remove-sala>
            </v-dialog>
            <span>&nbsp;</span>
            <v-dialog v-model="dialogImport" max-width="700px">
              <template class="container" v-slot:activator="{ on }">
                <button v-on="on" @click="importKey+=1" class="btn btn-secondary block"> Importar Dados </button>
              </template>
              <import :key="importKey" @close="close"></import>
            </v-dialog>
            <span>&nbsp;</span>
            <v-dialog v-model="dialogAdiconarWorkshop" max-width="700px">
              <template class="container" v-slot:activator="{ on }">
                <button v-on="on" @click="addWorkshopKey+=1" class="btn btn-secondary block"> Adicionar Workshop </button>
              </template>
              <add-workshop :key="addWorkshopKey" @close="close" @create="createWorkshop"></add-workshop>
            </v-dialog>
            <span>&nbsp;</span>
            <v-dialog v-model="dialogRemoverWorkshop" max-width="700px">
              <template class="container" v-slot:activator="{ on }">
                <button v-on="on" @click="removeWorkshopKey+=1" class="btn btn-secondary block"> Remover Workshop </button>
              </template>
              <remove-workshop :key="removeWorkshopKey" @close="close" @remove="removeWorkshop"></remove-workshop>
            </v-dialog>
          </v-card-text>
        </v-card>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
import CreateUser from "./create_user";
import RemoveUser from "./remove_user";
import Import from "./import_dados";
import AddWorkshop from "./add_workshop";
import RemoveWorkshop from "./remove_workshop";
import AddSala from "./add_sala";
import RemoveSala from "./remove_sala";

export default {
  props: ['user'],
  components: {
    "create-user": CreateUser,
    "remove-user": RemoveUser,
    "import": Import,
    "add-workshop": AddWorkshop,
    "remove-workshop": RemoveWorkshop,
    "add-sala": AddSala,
    "remove-sala": RemoveSala
  },
  data() {
    return {
      loading: 1,
      dialog:false,
      dialogImport:false,
      dialogAdiconarSala:false,
      dialogRemoverSala:false,
      dialogAdiconarWorkshop:false,
      dialogRemoverWorkshop:false,
      dialogRemoveUser:false,
      createUserKey: 0,
      importKey: 0,
      addSalaKey: 0,
      removeSalaKey: 0,
      addWorkshopKey: 0,
      removeWorkshopKey: 0,
      removeUserKey: 0,
      newUser: {},
      boxes: [],
      atividadesRestantes:0,
      userFoto: '',
    }
  },
  watch:{
    dialog(val) {
      val || this.close();
    },
    dialogImport(val) {
      val || this.close();
    },
    dialogAdiconarSala(val) {
      val || this.close();
    },
    dialogRemoverSala(val) {
      val || this.close();
    },
    dialogAdiconarWorkshop(val) {
      val || this.close();
    },
    dialogRemoverWorkshop(val) {
      val || this.close();
    },
    dialogRemoveUser(val) {
      val || this.close();
    },
  },
  methods: {
    linkTo(routePoint,routeId){
      if(routePoint!=this.$route.path){
        this.$emit("linkTo", routePoint, routeId);
      }
    },
    close() {
      this.dialog = false;
      this.dialogImport = false;
      this.dialogAdiconarSala = false;
      this.dialogRemoverSala = false;
      this.dialogAdiconarWorkshop = false;
      this.dialogRemoverWorkshop = false;
      this.dialogRemoveUser = false;
      this.newUser = {};
    },
    createUser(user) {
      axios.post("api/users", user).then(response => {
        if(response.status!=200){
          Vue.toasted.error('Algo correu mal... ' + response.data);
          return;
        }
        Vue.toasted.show("Utilizador criado com sucesso");
        this.close();
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    },
    createWorkshop(workshop) {
      axios.post("api/workshops", workshop).then(response => {
        if(response.status!=200){
          Vue.toasted.error('Algo correu mal... ' + response.data);
          return;
        }
        Vue.toasted.show("Workshop adicinonado com sucesso");
        this.close();
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    },
    createSala(sala) {
      axios.post("api/salas", sala).then(response => {
        if(response.status!=200){
          Vue.toasted.error('Algo correu mal... ' + response.data);
          return;
        }
        Vue.toasted.show("Sala adicinonada com sucesso");
        this.close();
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    },
    removeUser(user) {
      axios.delete(`/api/users/${user.id}`).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data.data);
          return;
        }
        Vue.toasted.show("Utilizador elimindado com sucesso");
        this.close();
      })
    },
    removeSala(sala) {
      axios.delete(`/api/salas/${sala.id}`).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data.data);
          return;
        }
        Vue.toasted.show("Sala removida com sucesso");
        this.close();
      })
    },
    removeWorkshop(workshop) {
      axios.delete(`/api/workshops/${workshop.id}`, {}).then(response => {
        if(response.status!=200){
          Vue.toasted.error(response.data.data);
          return;
        }
        Vue.toasted.show("Workshop removido com sucesso");
        this.close();
      })
    },
    getUserPhoto() {
      if(this.user.fotografia == null) {
        this.userFoto = '/storage/noUserPhoto.png';
      }
      this.userFoto = `/storage/fotos/${this.user.fotografia}`;
    },
    getAtividadesRestantes() {
      axios.get(`api/atividadesAno`).then(response => {
        this.atividadesRestantes = response.data;
      })
      .catch(response => {
        this.atividadesRestantes = 0;
        Vue.toasted.error('Não foi possível encontrar as atividades restantes');
      })
    }
  },
  mounted(){
    this.loading = 1;
  },
  created(){
    this.getUserPhoto();
    this.getAtividadesRestantes();
    this.boxes = [{
        id:0,
        class: 'head center',
        middle: {
          text: `Bem-vindo de volta`,
          template: `${this.user.nome}`,
        },
        img: {
          on: 1,
          url: `${this.userFoto}`
        },
        action:{
          text: "Veja os detalhes da sua conta",
          linkTo: "/backoffice/perfil/",
          routeId: 1,
        }
      }, {
        id:1,
        class: 'head center',
        middle: {
          text: `Atividades por realizar neste ano:`,
          atividadesRestantes: `${this.atividadesRestantes}`,
        },
        img: {
          on: 1,
          url: "/storage/Logo_DEI.png"
        },
        action: {
          text: "Veja as atividades",
          linkTo: "/backoffice/atividades/",
          routeId: 0,
        }
      },
    ];
  
    this.$emit("linkTo", "/backoffice/dashboard", 0);
  }
}
</script>

<style scoped>
  .caixa {
    min-width:400px;
    /* height:460px; */
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

  .caixa-texto{
    width: 440px;
    height: 130px;
  }
</style>