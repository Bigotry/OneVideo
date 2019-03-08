import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false

Vue.prototype.$serverUrl 	= 'http://onevideo.onebase.org';
Vue.prototype.$ak 			= 'l2V|gfZp{8`;jzR~6Y1_';
Vue.prototype.$listRows 	= 10;


App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()