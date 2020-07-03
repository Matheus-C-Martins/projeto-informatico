<template>
  <v-content>
    <v-navigation-drawer app v-model="drawer" :mini-variant.sync="mini" permanent floating dark color="#000000">
      <v-list dense nav class="py-0">
        <v-list-item :class="mini && 'px-0'">
          <v-list-item-avatar>
            <v-img v-if="user!=undefined" :src="getUserPhoto"></v-img>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title class="bolded">{{this.user.nome}}</v-list-item-title>
          </v-list-item-content>
          <v-btn icon @click.stop="mini = !mini" v-if="!mini">
            <v-icon>mdi-chevron-left</v-icon>
          </v-btn>
        </v-list-item>
        <v-list-item>
          <v-icon>mdi-logout</v-icon>
          <span>&nbsp;</span>
          <button class="btn btn-primary" @click.stop.prevent="logout" @click.stop="mini = !mini" v-if="!mini"> Logout </button>
        </v-list-item>
      </v-list>
      
      <v-divider></v-divider>

      <v-list nav shaped>
        <v-list-item-group :key="currentPath" :item="item" >
          <v-list-item active-class="activated" :class="{'activated v-list-item--active': item.link == currentPath }" :ripple="false"  @click="linkTo(item)"  v-for="(item) in items" :key="item.id">
            <v-list-item-icon>
              <v-icon v-text="item.icon"></v-icon>
            </v-list-item-icon>
            <span>&nbsp;</span>
            <v-list-item-content>
              <v-list-item-title v-text="item.title"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
  </v-content>
</template>

<script>
export default {
  props:[
    'user',
    'item',
    'currentPath',
  ],
  data() {
    return {
      drawer: true,
      mini: false,
      items: [
        {
          id:0,
          title: "Dashboard",
          icon: "mdi-view-dashboard",
          component: "dashboard",
          link: "/backoffice/dashboard/"
        },
        {
          id:1,
          title: "Perfil",
          icon: "mdi-account-settings",
          component: "perfil",
          link: "/backoffice/perfil/"
        },
        {
          id:2,
          title: "Atividades",
          icon: "mdi-book-open-page-variant",
          component: "atividades",
          link: "/backoffice/atividades/"
        },
        {
          id:3,
          title: "Cursos",
          icon: "mdi-inbox-multiple",
          component: "cursos",
          link: "/backoffice/cursos/"
        },
        {
          id:4,
          title: "Academia de Verão",
          icon: "mdi-home-floor-a",
          component: "academia",
          link: "/backoffice/academia/"
        },
        {
          id:5,
          title: "Docentes",
          icon: "mdi-account-tie",
          component: "docentes",
          link: "/backoffice/docentes/"
        },
        {
          id:6,
          title: "Contactos",
          icon: "mdi-phone",
          component: "contactos",
          link: "/backoffice/contactos/"
        },
        {
          id:7,
          title: "Escolas",
          icon: "mdi-school",
          component: "escolas",
          link: "/backoffice/escolas/"
        },
        {
          id:8,
          title: "Estatísticas",
          icon: "mdi-chart-areaspline",
          component: "estatisticas",
          link: "/backoffice/estatisticas/"
        },
      ],
    };
  },
  methods: {
    logout() {
      this.$emit("logout");
    },
    linkTo(item){
      if(item.link!=this.$route){
        this.$emit("linkTo", item.link, item.id);
      }
    },
  },
  computed: {
    getUserPhoto() {
      if(this.user.fotografia == null) {
        return '/storage/noUserPhoto.png';
      }
      return `/storage/fotos/${this.user.fotografia}`;
    },
  },
  watch: {
    $route(to, from) {
      this.currentPath = to.path;
    }
  },
}
</script>

<style scoped>
a {
  color: white;
  text-decoration: none;
}

.activated {
  color: white;
  pointer-events:none;
  background-color: #6c757d;
}

.bolded{
  font-weight: bold;
}
</style>
