<template>
  <span>
    <v-app v-if="user!==undefined" id="inspire">
      <nav>
        <sidebar
          @linkTo="linkTo"
          :currentPath="currentPath"
          :item="item_sidebar"
          :user="user"
          @logout="logout"
          :key="componentKey"
        ></sidebar>
        <v-footer absolute color="#FFFFFF">
          <v-row dense>
            <v-card tile flat width="100%">
              <v-card-text>
                <v-row align="end" justify="end">
                  ©2020, feito por Matheus Martins e Ivan Silva
                </v-row>
              </v-card-text>
            </v-card>
          </v-row>
        </v-footer>
      </nav>
      <v-content fluid class="mx-4 mt-3">
        <transition name="fade">
        <router-view :items_backoffice="items_backoffice" :user="user" name="backoffice" @linkTo="linkTo" @getUser="getAuthUser" :key="componentKey"></router-view>
        </transition>
      </v-content>
    </v-app>
    <v-app v-else id="inspire">
      <v-container style="widht:100%; height:100%; text-align:center" fluid>A carregar...</v-container>
    </v-app>
  </span>
</template>

<script>
import Sidebar from "./sidebar";

export default {
  data() {
    return {
      componentKey:0, 
      user: undefined,
      token: localStorage.getItem("user-token"),
      currentPath: "",
      showLinks:0,
      /* BACKOFFICE FAST LINKS*/
      items_backoffice: [
        {
          id: 0,
          title: "Dashboard",
          text: "Veja as atividades que ainda faltam realizar este, importe dados, \
                 adicione/remova salas ou adicione/remova workshops",
          img: "/storage/dash.png",
          link: "/backoffice/dashboard/"
        },
        {
          id: 1,
          title: "Perfil",
          text: "Veja as suas informações",
          img: "/storage/acc.png",
          link: "/backoffice/perfil/"
        },
        {
          id: 2,
          title: "Atividades",
          text: "Veja as informações das atividades e faça a sua gestão",
          img: "/storage/atividades.png",
          link: "/backoffice/atividades/"
        },
        {
          id: 3,
          title: "Cursos",
          text: "Veja os cursos disponibilizados pelo DEI e faça a sua gestão",
          img: "/storage/cursos.png",
          link: "/backoffice/cursos/"
        },
        {
          id: 3,
          title: "Academia de Verão",
          text: "Veja os participantes e as atividades das academias de verão. Marque a academia de verão para este ano",
          img: "/storage/academia.png",
          link: "/backoffice/academia/"
        },
        {
          id: 5,
          title: "Contactos",
          text: "Veja os contactos que nos foram efetuados e os que efetuamos",
          img: "/storage/contactos.png",
          link: "/backoffice/contactos/"
        },
        {
          id: 6,
          title: "Docentes",
          text: "Veja os docentes da ESTG que vão estar presentes nas atividades e na academia de verão",
          img: "/storage/docentes.png",
          link: "/backoffice/docentes/"
        },
        {
          id: 7,
          title: "Escolas",
          text: "Veja as escolas que nos contactaram",
          img: "/storage/escolas.png",
          link: "/backoffice/escolas/"
        },
        {
          id: 8,
          title: "Estatísticas",
          text: "Veja as estatísticas",
          img: "/storage/stats.png",
          link: "/backoffice/estatisticas/"
        },
        {
          id: 9,
          title: "Inquéritos",
          text: "Faça a gestão dos inquéritos e veja as suas respostas",
          img: "/storage/inqueritos.png",
          link: "/backoffice/inqueritos/"
        },
      ],
      item_sidebar: null,
      transparent: "rgba(255, 255, 255, 0)"
    };
  },
  components: {
    sidebar: Sidebar,
  },
  methods: {
    /* LINK PARA OUTRAS ROTAS */
    linkTo: function(newRoute, routeId) {
      this.item_sidebar = routeId;
      this.$router.push(newRoute).catch(err => err);
      this.currentPath = this.$route.path;
    },
    logout() {
      axios.post("/api/logout").then(response => {
          localStorage.removeItem("user-token");
          axios.defaults.headers.common.Authorization = undefined; //pôr o header do token a null/undefined
          this.$router.push("/login");
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal... ');
      });
    },
    getAuthUser: function() {
      axios.get("/api/user").then(response => {
        this.user = response.data;
        this.componentKey += 1; 
      })
      .catch(response => {
        Vue.toasted.error('Algo correu mal... ');
      })
      .finally(() => this.showLinks = 1);
    },
  },
  mounted() {
    this.getAuthUser();
  },
  watch: {
    $route(to, from) {
      this.currentPath = to.path;
    }
  },
};
</script>

<style scoped>
.v-card {
  transition: opacity 0.2s ease-in-out;
}

.v-card:not(.on-hover) {
  opacity: 0.6;
}

.show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>
