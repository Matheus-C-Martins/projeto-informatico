<template>
  <v-container fluid class="pa-0">
    <v-card tile flat>
      <v-card-title> Bem-vindo ao DEI@Tour </v-card-title>
    </v-card>
    <v-container class="pa-4 text-center">
      <v-row class="fill-height" align="center" justify="center">
        <template v-for="(item, i) in items_backoffice">
          <v-col :key="i">
            <v-hover v-slot:default="{ hover }">
              <v-card dark :elevation="hover ? 100 : 2" :class="{ 'on-hover': hover }" @click="linkTo(item.link,item.id)">
                <v-img :src="item.img" height="240px">
                  <v-card-title style="margin-left:25px" class="title">
                    <v-row class="fill-height flex-column" justify="space-between">
                      <p class="mt-4 subheading text-left font-weight-bold">{{ item.title }}</p>
                      <div>
                        <p style="max-width:55%" class="mt-4 subheading text-left text-left font-weight-light">{{ item.text }}</p>
                        <p class="mt-4 subheading text-left text-left font-weight-light">{{ item.subtext }}</p>
                      </div>
                    </v-row>
                  </v-card-title>
                </v-img>
              </v-card>
            </v-hover>
          </v-col>
        </template>
      </v-row>
    </v-container>
  </v-container>
</template>

<script>
export default {
  props: [
    'backoffice',
    'items_backoffice'
  ],
  data() {
    return {
    }
  },
  methods: {
    linkTo(routePoint,routeId){
      if(routePoint!=this.$route.path){
        this.$emit("linkTo",routePoint,routeId);
      }
    },
  },
}
</script>
