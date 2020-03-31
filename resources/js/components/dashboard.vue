<template>
  <v-app id="inspire">
    <v-container fluid class="pa-0">

    </v-container>
  </v-app>
</template>

<script>
export default {
  props: ['user'],
  data() {
    return {
      loading: 1,
    }
  },
  methods: {
    linkTo(routePoint,routeId){
      if(routePoint!=this.$route.path){
        this.$emit("linkTo", routePoint, routeId);
      }
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
