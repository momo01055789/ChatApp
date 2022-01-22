require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
import VueChatScroll from 'vue-chat-scroll'
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css' 
// optional set default imeout, the default is 10000 (10 seconds).
Vue.use(Toaster, {timeout: 3000})
Vue.use(VueChatScroll)

Vue.component('message', require('./components/Message.vue').default);
Vue.component('chat-room', require('./components/ChatRoom.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
