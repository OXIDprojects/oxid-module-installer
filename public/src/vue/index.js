import Vue from 'vue';

import { store } from './_store';
import { router } from './_helpers';
import App from './app/App';

import Header from './Components/Header_footer/Header.vue'
import Footer from './Components/Header_footer/Footer.vue';

import Search from './Components/Search.vue';
import Packages from './Components/Packages/Packages.vue';

Vue.component('app-header', Header)
Vue.component('app-footer', Footer)
Vue.component('app-search', Search)
Vue.component('app-packages', Packages)

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});