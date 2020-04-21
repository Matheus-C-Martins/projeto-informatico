require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import Welcome from './components/welcome/index';
import Backoffice from './components/backoffice';
import Home from './components/home';
import Dashboard from './components/dashboard/index';
import Perfil from './components/perfil/index';
import Contactos from './components/contactos/index';
import Docentes from './components/docentes/index';
import Escolas from './components/escolas/index';
import UploadImage from 'vue-upload-image';
import VueApexCharts from 'vue-apexcharts';
import Toasted from 'vue-toasted';

/* Vuetify */
Vue.use(Vuetify);
const opts = {};
const vuetify =  new Vuetify(opts);

/* Components */
Vue.component('Welcome', Welcome);
Vue.component('backoffice', Backoffice);
Vue.component('dashboard', Dashboard);
Vue.component('perfil', Perfil);
Vue.component('contactos', Contactos);
Vue.component('docentes', Docentes);
Vue.component('escolas', Escolas);
Vue.component('upload-image', UploadImage);
Vue.component('apexchart', VueApexCharts);

/* ROUTE DEFINITION */
Vue.use(VueRouter);

const routes = [
    {path:'/', component:Welcome},
    {path:'/backoffice', redirect: '/backoffice/home', component:Backoffice,
        beforeEnter: (to, from, next) => {
            if(localStorage.getItem("user-token") != null){
                next(next)
            }else{
                next(false)
            }
        },
        children:[
            {
                path: 'home',
                components: {
                    backoffice: Home
                }
            },
            {
                path: 'dashboard',
                components: {
                    backoffice: Dashboard
                }
            },
            {
                path: 'perfil',
                components: {
                    backoffice: Perfil
                }
            },
            {
                path: 'contactos',
                components: {
                    backoffice: Contactos
                }
            },
            {
                path: 'docentes',
                components: {
                    backoffice: Docentes
                }
            },
            {
                path: 'escolas',
                components: {
                    backoffice: Escolas
                }
            },
        ]
    },
]

const router = new VueRouter({
    routes,
});

/* Notificacoes */
Vue.use(Toasted, {
        position: "top-right",
        duration : 3000
    },
    {
        action : {
            text : 'Cancel',
            onClick : (e, toastObject) => {
                toastObject.goAway(0);
            }
        },
    }
);

/* VUE INSTANCE */
const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router,
    methods: {

    },
    components: {
        UploadImage
    },
    created() {
        /* Quando damos refresh à página (nova instancia do Vue), vamos ter que ver se já existe
           o user-token no localStorage, para mantermos o Header de Auth do Bearer token */
        if(localStorage.getItem("user-token") != null){
            axios.defaults.headers.common.Authorization ="Bearer " + localStorage.getItem("user-token")
        }
    }
});
