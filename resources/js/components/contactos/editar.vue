<template>
  <v-card>
    <v-card-title> Editar Contacto </v-card-title>
    <v-card-text style="padding-bottom: 0px;">
      <v-form>
        <v-row>
          <v-col>
            <v-text-field clearable outlined label="Telefone" v-model="contacto.telefone" dense hide-details @keypress.enter.stop.prevent="save"></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-text-field clearable outlined label="Email" v-model="contacto.email" dense hide-details @keypress.enter.stop.prevent="save"></v-text-field>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <button class="btn btn-primary" @click.stop.prevent="close"> Cancelar </button>
      <span>&nbsp;</span>
      <button class="btn btn-secondary" @click.stop.prevent="save"> Editar </button>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  props: [
    'contacto',
  ],
  data: function() {
    return {
    };
  },
  watch: {
    contacto: function(newVal, oldVal){
      newVal;
    }
  },
  methods: {
    close() {
      this.$emit("close");
    },
    save() {
      this.$emit("save", this.contacto);
    },
  },
}
</script>
