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
    <v-card>
      <v-form>
        <v-text-field label="Ficheiro CSV"
          v-model="fileName"
          @click="pickCSV"
          hide-details
          readonly
          outlined
          dense>
        </v-text-field>
        <input
          type="file"
          style="display: none"
          ref="file"
          accept=".csv"
          @change="fileChanged">
        <button @click.stop.prevent="importCsv" class="btn btn-secondary block"> Teste </button>
      </v-form>
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
      fileName: '',
      file: '',
      csv: '',
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
    pickCSV () {
      this.$refs.file.click();
    },
    fileChanged (e) {
      const files = e.target.files
      if(files[0] !== undefined) {
        this.fileName = files[0].name
        this.csv = this.$refs.file.files[0]
      } else {
        this.fileName = '';
        this.file = '';
        this.csv = '';
      }
    },
    importCsv(){
      if(this.csv == '' || this.csv == undefined || this.csv == null) {
        Vue.toasted.error('Tem de inserir um ficheiro');
        return;
      }
      let formData = new FormData();
      formData.append('csv_file', this.csv);

      axios.post("api/importSalas", formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
        if(response.status!=200) {
          Vue.toasted.error(response.data);
          return;
        }
        Vue.toasted.show(response.data);
        this.close();
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    }
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
