<template>
  <v-container fluid>
    <v-card flat tile>
      <v-card-title> Estatísticas </v-card-title>
    </v-card>
    <v-divider style="margin-top: 0px"></v-divider>
    <v-container :key="graph.id" fluid class="pa-0">
      <div v-show="graph.show">
        <v-tabs color="#000000" v-model="tab[graph.id]" centered>
          <v-tab v-for="type in graph.types" :key="type.label" @click="graph.id==0?getData(type.value, type.title):graph.id==1?getDataParticipantes(type.value, type.title):getDataWorkshops(type.value, type.title)" :href="type.tab" >{{type.label}}</v-tab>
        </v-tabs>
        <v-tabs-slider></v-tabs-slider>
        <v-tabs-items v-model="tab[graph.id]">
          <v-tab-item v-for="(type, index) in graph.types" :key="type.label" :value="'tab-' + index">
            <template v-if="graph.id==0">
              <div v-if="grafico">
                <apexchart :key="options.theme.palette" height="400" :type="estatisticas.tipo=='num'?'bar':'donut'" :options="estatisticas.tipo=='ratio'?ratioOptions:options" :series="estatisticas.tipo=='ratio'?seriesRatio:series"></apexchart>
              </div>
              <div align="center" justify="center" v-else>
                <div style="height:410px"> <v-progress-circular indeterminate color="#000000"></v-progress-circular> </div>
              </div>
            </template>
            <template v-if="graph.id==1">
              <div v-if="grafico">
                <apexchart :key="options.theme.palette" height="400" :type="estatisticas.tipo=='num'?'bar':'donut'" :options="estatisticas.tipo=='ratio'?ratioOptions:options" :series="estatisticas.tipo=='ratio'?seriesRatio:series"></apexchart>
              </div>
              <div align="center" justify="center" v-else>
                <div style="height:410px"> <v-progress-circular indeterminate color="#000000"></v-progress-circular> </div>
              </div>
            </template>
            <template v-if="graph.id==2">
              <div v-if="grafico">
                <apexchart :key="options.theme.palette" height="400" :type="estatisticas.tipo=='num'?'bar':'donut'" :options="estatisticas.tipo=='ratio'?ratioOptionsWorkshop:options" :series="estatisticas.tipo=='ratio'?seriesRatioWorkshop:seriesWorkshop"></apexchart>
              </div>
              <div align="center" justify="center" v-else>
                <div style="height:410px"> <v-progress-circular indeterminate color="#000000"></v-progress-circular> </div>
              </div>
            </template>
          </v-tab-item>
        </v-tabs-items>
      </div>
    </v-container>

    <v-expansion-panels focusable accordion>
      <v-expansion-panel>
        <v-expansion-panel-header> Alterar Métricas </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-form>
            <v-row align="center" justify="center">
              <v-col class='title'> Tempo </v-col>
              <v-col>
                <v-radio-group style="margin-top:0px" hide-details row v-model="estatisticas.metrica.tempo">
                  <v-radio color="#000000" v-on:change="mudarTempoDefault(tempo.value)" v-for="tempo in metricas.tempo" :key="tempo.label" :label="tempo.label" :value="tempo.value"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col v-if="estatisticas.metrica.tempo == 'mes'" align="center" justify="center"> Dos últimos </v-col>
              <v-col align="center" justify = "center">
                <div v-for="tempo in metricas.tempo" :key="tempo.label">
                  <v-select
                    color="#000000"
                    dense
                    outlined
                    hide-details
                    @change="getData(estatisticas.tipo, estatisticas.title)"
                    v-if="estatisticas.metrica.tempo == tempo.value"
                    :items="tempo.values"
                    v-model="estatisticas.metrica.valor">
                  </v-select>
                </div>
              </v-col>
              <v-col v-if="estatisticas.metrica.tempo=='mes'" :cols="1" align="center" justify="center"> Meses </v-col>
            </v-row>
          </v-form>
          <v-divider></v-divider>
          <v-row align="center" justify="center">
            <v-col class='title'> Valores </v-col>
            <v-col>
              <v-select
                v-model="id"
                :items="valores"
                item-value="id"
                hide-details
                outlined
                dense>
              </v-select>
            </v-col>
          </v-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </v-container>
</template>

<script>

export default {
  props:['user'],
  data () {
    return {
      grafico: false,
      estatisticas: {
        'tipo': 'num',
        'metrica': {
          'tempo': 'ano',
          'valor': '2020',
        },
        'titulo': 'Quantidade de Atividades',
      },
      metricas: {
        tempo:[
          {
            'label': 'Meses',
            'value': 'mes',
            'values': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
          },
          {
            'label': 'Anos',
            'value': 'ano',
            'values': [],
          },
        ],
      },
      monthsNames: ['Jan','Fev','Mar','Abr','Maio','Jun','Jul','Ago','Set','Out','Nov','Dez'],
      tab: [ null, null ],
      tiposGraficosAtividades:[
        {
          tab: "#tab-0",
          label:"Quantidade",
          value:"num",
          title:"Número de Atividades",
        },
        {
          tab: "#tab-1",
          label:"Proporção",
          value:"ratio",
          title:"Proporção de Atividades",
        },
      ],
      id: 0,
      graph: {
        id: 0,
        statistics: {},
        options: {},
        optionsRatio: {},
        series: {},
        seriesRatio: {},
        ratio:1,
        show:true,
        types:[],
      },
      valores: [
        { id: 0, text: "Atividades" },
        { id: 1, text: "Participantes" },
        { id: 2, text: "Workshops" },
      ],
      options: {
        dataLabels: { enabled: false },
        theme:{ palette: 'palette3' },
        chart: {
          zoom: { enabled: false },
          toolbar:{ show:false },
          id: 'vuechart',
          height: '200',
          width: '90%',
        },
        plotOptions: {
          bar: {
            horizontal: false,
            endingShape: "rounded"
          }
        },
        stroke: { show: true, width: 3, colors: ["transparent"] },
        markers: { size: 0 },
        title: {
          text: undefined,
          align: 'left',
          floating: false,
          style: {
            fontSize: '24px',
            color: '#263238'
          },
        },
        xaxis: { type: 'category', categories: [] },
      },
      seriesRatio:[],
      seriesRatioWorkshop:[],
      series: [
        {
          name: 'Workshop',
          data: []
        },
        {
          name: 'Dia ESTG',
          data: []
        },
        {
          name: 'Seminário',
          data: []
        },
        {
          name: 'Visita à Escola',
          data: []
        }
      ],
      seriesWorkshop: [],
      ratioOptions: {
        theme: {
          palette: 'palette3',
        },
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          floating: false,
        },
        title: {
          text: undefined,
          align: 'left',
          floating: false,
          style: {
            fontSize:  '24px',
            color:  '#263238'
          },
        },
        labels: ['Workshop', 'Dia ESTG', 'Seminário', 'Visita à Escola'],
      },
      ratioOptionsWorkshop: {
        theme: {
          palette: 'palette3',
        },
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          floating: false,
        },
        title: {
          text: undefined,
          align: 'left',
          floating: false,
          style: {
            fontSize:  '24px',
            color:  '#263238'
          },
        },
        labels: [],
      },
      loading: false,
      errored: false,
    };
  },
  watch: {
    id(){
      this.graph.id = this.id;
      if(this.graph.id == 0) {
        this.getData(this.estatisticas.tipo, this.estatisticas.metrica.valor);
      } else if(this.graph.id == 1) {
        this.getDataParticipantes(this.estatisticas.tipo, this.estatisticas.metrica.valor);
      } else {
        this.getDataWorkshops(this.estatisticas.tipo, this.estatisticas.metrica.valor);
      }
    }
  },
  methods: {
    getData(tipo, titulo) {
      this.grafico = false;
      if(tipo == 'ratio') {
        titulo = "Proporção de Atividades";
      } else {
        titulo = "Número de Atividades";
      }
      axios.get(`api/atividades/estatisticas/${tipo}/${this.estatisticas.metrica.tempo}/${Number(this.estatisticas.metrica.valor)}/`).then(response => {
        this.takeData(response, tipo, titulo);
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal... ");
      });
    },
    getDataParticipantes(tipo, titulo) {
      this.grafico = false;
      if(tipo == 'ratio') {
        titulo = "Proporção de Participantes";
      } else {
        titulo = "Número de Participantes";
      }
      axios.get(`api/atividades/participantes/${tipo}/${this.estatisticas.metrica.tempo}/${Number(this.estatisticas.metrica.valor)}/`).then(response => {
        this.takeData(response, tipo, titulo);
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal... ");
      });
    },
    getDataWorkshops(tipo, titulo) {
      this.grafico = false;
      if(tipo == 'ratio') {
        titulo = "Proporção de Worlshops";
      } else {
        titulo = "Número de Workshops";
      }
      axios.get(`api/atividades/workshops/${tipo}/${this.estatisticas.metrica.tempo}/${Number(this.estatisticas.metrica.valor)}/`).then(response => {
        this.takeDataWorkshop(response, tipo, titulo);
      })
      .catch(response => {
        Vue.toasted.error("Algo correu mal... ");
      });
    },
    takeData(response, tipo, titulo) {
      this.estatisticas.tipo = tipo;
      if(tipo == 'ratio'){
        this.ratioOptions.title.text = titulo;
        this.estatisticas.titulo = this.ratioOptions.title.text;

        var total = Number(response.data.workshop[0].value) + Number(response.data.diaEstg[0].value) + Number(response.data.seminario[0].value);
        var workshop = Number(response.data.workshop[0].value)*100/total;
        var diaEstg = Number(response.data.diaEstg[0].value)*100/total;
        var seminario = Number(response.data.seminario[0].value)*100/total;
   
        if(isNaN(workshop) || isNaN(diaEstg) || isNaN(seminario)){
          Vue.toasted.error("Sem dados para apresentar");
          this.grafico = true;
          return;
        }

        this.seriesRatio=[workshop, diaEstg, seminario];
        this.grafico = true;
        return;
      }
      this.options.xaxis.categories =this.monthsNames;

      this.series[0].data = this.preencherUndefined(response.data.workshop);
      this.series[1].data = this.preencherUndefined(response.data.diaEstg);
      this.series[2].data = this.preencherUndefined(response.data.seminario);
      this.series[3].data = this.preencherUndefined(response.data.visita);
      
      this.options.title.text = titulo;
      this.estatisticas.titulo = this.options.title.text;
      this.grafico = true;
    },
    takeDataWorkshop(response, tipo, titulo) {
      this.estatisticas.tipo = tipo;
      if(tipo == 'ratio'){
        this.ratioOptions.title.text = titulo;
        this.estatisticas.titulo = this.ratioOptions.title.text;
        var total = 0;
        response.data.workshops.forEach(tipo => {
          total += tipo.value;
        });
        this.seriesRatioWorkshop = [];
        this.ratioOptionsWorkshop.labels = [];
        response.data.workshops.forEach(tipo => {
          this.ratioOptionsWorkshop.labels.push(tipo.nome);
          this.seriesRatioWorkshop.push(Number(tipo.value)*100/total);
        });

        this.grafico = true;
        return;
      }
      this.options.xaxis.categories =this.monthsNames;

      this.seriesWorkshop = [];
      var name = '';
      var data = [];
      var aux;
      response.data.workshops.forEach(tipo => {
        name = tipo.nome;
        aux = [];
        aux[tipo.month - 1] = tipo.value;
        for (let i = 0; i < aux.length; i++) {
          if(aux[i] === undefined){
            aux[i] = 0;
          }
        }
        for (let i = aux.length; i <= 11; i++) {
          aux[i] = 0;
        }
        data = aux;

        this.seriesWorkshop.push({name, data})
      });
      
      this.options.title.text = titulo;
      this.estatisticas.titulo = this.options.title.text;
      this.grafico = true;
    },
    preencherUndefined(array) {
      var aux = [];
      array.forEach(a => {
        aux[a.month - 1] = a.value;
      });
      for (let i = 0; i < aux.length; i++) {
        if(aux[i] === undefined){
          aux[i] = 0;
        }
      }
      for (let i = aux.length; i <= 11; i++) {
        aux[i] = 0;
      }
      return aux;
    },
    mudarTempoDefault(tempo) {
      if(tempo == 'mes') {
        this.estatisticas.metrica.valor = 1;
        this.estatisticas.metrica.tempo = 'mes';
      } else {
        this.estatisticas.metrica.valor = new Date().getFullYear();
        this.estatisticas.metrica.tempo = 'ano';
      }
      this.getData(this.estatisticas.tipo, this.estatisticas.titulo);
    },
  },
  created() {
    var anoAtual = new Date().getFullYear(), anos = [];
    this.estatisticas.metrica.valor = anoAtual;
    var inicio = 2000;
    while (inicio <= anoAtual) {
        anos.push(inicio++);
    }
    this.metricas.tempo[1].values = anos.reverse();

    this.graph.types = this.tiposGraficosAtividades;
    this.$emit("linkTo", "/backoffice/estatisticas", 1);
  },
  mounted() {
    if(this.graph.id == 0) {
      this.getData(this.estatisticas.tipo, this.estatisticas.titulo);
    } else if(this.graph.id == 1) {
      this.getDataParticipantes(this.estatisticas.tipo, this.estatisticas.titulo);
    } else {
      this.getDataWorkshops(this.estatisticas.tipo, this.estatisticas.titulo);
    }
  },
}
</script>