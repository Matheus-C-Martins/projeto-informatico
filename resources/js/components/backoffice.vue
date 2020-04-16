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
      <v-container style="widht:100%; height:100%; text-align:center" fluid>Loading...</v-container>
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
      /* backoffice FAST LINKS ITEMS */
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
          title: "Contactos",
          text: "Veja os contactos",
          img: "/storage/contactos.png",
          link: "/backoffice/contactos/"
        },
      ],
      /* SIDE BAR ITEMS */
      item_sidebar: null,
      transparent: "rgba(255, 255, 255, 0)"
    };
  },
  components: {
    sidebar: Sidebar,
  },
  methods: {
    /* LINK TO OTHER ROUTES */
    linkTo: function(newRoute, routeId) {
      this.item_sidebar = routeId;
      this.$router.push(newRoute).catch(err => err);
      this.currentPath = this.$route.path;
    },
    /* LOGOUT */
    logout: function() {
      axios.post("/api/logout").then(response => {
          localStorage.removeItem("user-token");
          axios.defaults.headers.common.Authorization = undefined; //pôr o header do token a null/undefined
          this.$router.push("/");
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
        console.log("->"+response)
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
