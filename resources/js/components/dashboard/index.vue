<template>
  <v-app id="inspire">
    <v-card outlined>
      <v-container fluid class="pa-0">
        <v-dialog v-if="user.tipo == 'a'" v-model="dialog" max-width="700px">
          <template class="container" v-slot:activator="{ on }">
            <button v-on="on" @click="createUserKey+=1" class="btn btn-secondary block"> Criar Novo Utilizador </button>
          </template>
          <createUser :newUser="newUser" :key="createUserKey" @create="createUser" @close="close"></createUser>
        </v-dialog>
      </v-container>
    </v-card>
  </v-app>
</template>

<script>
import CreateUser from "./create_user";

export default {
  props: ['user'],
  components: {
    createUser: CreateUser
  },
  data() {
    return {
      loading: 1,
      dialog:false,
      createUserKey: 0,
      newUser: {},
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
      this.newUser = {};
    },
    createUser(user){
      axios.post("api/users", user).then(response => {
        if(response.status!=200){
          Vue.toasted.error('Algo correu mal... '+response.data);
          return;
        }
        Vue.toasted.show("Utilizador Criado Com Sucesso");
        this.close();
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    },
  },
  mounted(){
    this.loading = 1;
  },
  created(){
    this.$emit("linkTo", "/backoffice/dashboard", 0);
  }
}
</script>

<style scoped>

</style>
