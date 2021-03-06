/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vuex from 'vuex';
import StoreData from './store';
import VueRouter from 'vue-router';
import {routes} from './routes';
import MainApp from './components/MainApp.vue';
import VueToast from 'vue-toast-notification';
import {init} from './helpers/init';

window.Vue.use(VueRouter);
window.Vue.use(Vuex);
window.Vue.use(VueToast);

const store = new Vuex.Store(StoreData);
const router = new VueRouter({
    routes,
    mode: 'history'
});

/**
 * init application
 */
init(store, router);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
    router,
    components: {
        MainApp
    }
});
