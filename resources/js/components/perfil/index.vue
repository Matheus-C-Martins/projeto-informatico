<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Perfil </v-card-title>
    </v-card>
    <v-divider style="margin-top: 0px"></v-divider>

    <v-card tile dark color="#000000">
      <v-form align="center">
        <v-container style="margin-top:30px">
          <v-row align="center">
            <v-col>
              <v-avatar size="125">
                <v-img v-if="user!=undefined" :src="getUserPhoto"></v-img>
              </v-avatar>
            </v-col>
            <v-col>
              <v-card color="#6c757d" class="mx-auto pa-6">
                <h6> Nome: </h6> {{user.nome}}
              </v-card>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-card color="#6c757d" class="mx-auto pa-6">
                <h6> Tipo: </h6>
                <div v-if="user.tipo == 'g'"> Gestor </div>
                <div v-if="user.tipo == 'a'"> Administrador </div>
              </v-card>
            </v-col>
            <v-col>
              <v-card color="#6c757d" class="mx-auto pa-6">
                <h6> Email: </h6> {{user.email}}
              </v-card>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="d-flex flex-row-reverse">
              <v-dialog v-model="dialog" max-width="700px">
                <template class="container" v-slot:activator="{ on }">
                  <button v-on="on" @click.stop.prevent="showUpdateProfile" class="btn btn-primary"> Editar Perfil </button>
                </template>
                <update :key="updateProfileKey" :updatedUser="profile" @update-profile="updateProfile" @cancel-update="close"></update>
              </v-dialog>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
import UpdateProfile from "./update";

export default {
  props: ["user"],
  components: {
    update: UpdateProfile
  },
  data() {
    return {
      index: true,
      dialog: false,
      updateProfileKey: 0,
      profile: {
        nome: this.user.nome,
        current_password: null,
        new_password: "",
        new_password_confirmation: "",
        fotografia: ""
      }
    };
  },
  methods: {
    showUpdateProfile: function() {
      this.updateProfileKey += 1;
    },
    updateProfile: function(updatedUser) {
      axios.put("api/users/update/" + this.user.id, updatedUser).then(response => {
        if(response.status!=200){
          Vue.toasted.error("Algo correu mal... "+response.data);
          return;
        }
        this.close;
        this.$emit("getUser");
        Vue.toasted.show(response.data);
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal...');
      });
    },
    close() {
      this.dialog = false;
      this.profile = {
        nome: this.user.nome,
        current_password: null,
        new_password: "",
        new_password_confirmation: "",
        fotografia: ""
      };
    },
  },
  created(){
    this.$emit("linkTo", "/backoffice/perfil", 1);
  },
  computed: {
    getUserPhoto: function() {
      if(this.user.fotografia == null){
        return '/storage/noUserPhoto.png';
      }
      return `/storage/fotos/${this.user.fotografia}`;
    },
  },
  watch: {
    dialog (val) {
      val || this.close()
    },
    user: function(newVal, oldVal) {
      newVal;
    }
  },
};
</script>
