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
          text: "Veja como estão a decorrer as atividades",
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
          text: "Veja as atividades",
          img: "/storage/atividades.png",
          link: "/backoffice/atividades/"
        },
        {
          id: 3,
          title: "Cursos",
          text: "Veja os cursos",
          img: "/storage/cursos.png",
          link: "/backoffice/cursos/"
        },
        {
          id: 3,
          title: "Academia de Verão",
          text: "Veja o academia de verão",
          img: "/storage/academia.png",
          link: "/backoffice/academia/"
        },
        {
          id: 5,
          title: "Contactos",
          text: "Veja os contactos",
          img: "/storage/contactos.png",
          link: "/backoffice/contactos/"
        },
        {
          id: 6,
          title: "Docentes",
          text: "Veja os docentes",
          img: "/storage/docentes.png",
          link: "/backoffice/docentes/"
        },
        {
          id: 7,
          title: "Escolas",
          text: "Veja as escolas",
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
