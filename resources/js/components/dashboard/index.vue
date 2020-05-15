<template>
  <v-app id="inspire">
    <v-card flat>
      <v-card-title> Dashboard
        <v-spacer></v-spacer>
        <v-dialog v-if="user.tipo == 'a'" v-model="dialog" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="createUserKey+=1" class="btn btn-secondary block"> Criar Novo Utilizador </button>
          </template>
          <createUser :newUser="newUser" :key="createUserKey" @create="createUser" @close="close"></createUser>
        </v-dialog>
      </v-card-title>
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
                <p v-if="box.middle.atividadesRestantes==1 && atividadesRestantes!==undefined" class='display-1 font-weight-black'>{{atividadesRestantes}}</p>
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
          <v-dialog v-model="dialogImport" max-width="700px">
            <template class="container" v-slot:activator="{ on }">
              <button v-on="on" @click="importKey+=1" class="btn btn-secondary block"> Importar Dados </button>
            </template>
            <import :key="importKey" @close="close"></import>
          </v-dialog>
        </v-card-text>
      </v-card>
      </v-card-actions>
    </v-card>
  </v-app>
</template>

<script>
import CreateUser from "./create_user";
import Import from "./import_dados";

export default {
  props: ['user'],
  components: {
    createUser: CreateUser,
    import: Import
  },
  data() {
    return {
      loading: 1,
      dialog:false,
      dialogImport:false,
      createUserKey: 0,
      importKey: 0,
      newUser: {},
      boxes: [],
      atividadesRestantes:0,
      userFoto: '',
    }
  },
  watch:{
    dialog: function(val){
      val || this.close();
    },
  },
  methods: {
    linkTo(routePoint,routeId){
      if(routePoint!=this.$route.path){
        this.$emit("linkTo", routePoint, routeId);
      }
    },
    close: function(){
      this.dialog = false;
      this.dialogImport = false;
      this.newUser = {};
    },
    createUser(user){
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
    getUserPhoto() {
      if(this.user.fotografia == null) {
        this.userFoto = '/storage/noUserPhoto.png';
      }
      this.userFoto = `/storage/fotos/${this.user.fotografia}`;
    },
  },
  mounted(){
    this.loading = 1;
  },
  created(){
    this.getUserPhoto();
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
      }, /*{
        id:1,
        class: 'head center',
        middle: {
          text: `Atividades por realizar neste ano:`,
          atividadesRestantes: 1,
        },
        img: {
          on: 1,
          url: "/storage/logo.png"
        },
        action: {
          text: "Veja as atividades",
          linkTo: "/backoffice/atividades/",
          routeId: 0,
        }
      },*/
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