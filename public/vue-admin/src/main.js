import Vue from 'vue';
import iView from 'iview';
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import Routers from './router';
import Vuex from 'vuex';
import Util from './libs/util';
import App from './app.vue';
import 'iview/dist/styles/iview.css';
import './config/api_url';
import MakeSign from './plugins/makeSign';
import CommonAction from './plugins/commonAction';
import AMap from 'vue-amap';


Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueResource);
Vue.use(iView);
Vue.use(MakeSign);
Vue.use(CommonAction);
Vue.use(AMap);


AMap.initAMapApiLoader({
    key: 'd53b8232e38add4009fea5f32a32f65c',
    plugin: ['Autocomplete', 'PlaceSearch', 'Scale', 'OverView', 'ToolBar', 'MapType', 'PolyEditor', 'AMap.CircleEditor']
});

// 路由配置
const RouterConfig = {
    mode: '',
    routes: Routers
};
const router = new VueRouter(RouterConfig);

router.beforeEach((to, from, next) => {
    iView.LoadingBar.start();
    Util.title(to.meta.title);
    next();
});

router.afterEach(() => {
    iView.LoadingBar.finish();
    window.scrollTo(0, 0);
});

/** 请求劫持 http的异常状态码 */
Vue.http.interceptors.push((request, next) => {
    next((response) => {
    if(response.status ===401) {
        router.push('/login');
    }
    return response
})
})

Vue.prototype.commonAction = function (){

};


const store = new Vuex.Store({
    state: {
        adminInfo:[],
        roleOperateArr:[],
        tableHeight:document.body.clientHeight*0.92*0.70,
    },
    getters: {

    },
    mutations: {

    },
    actions: {

    }
});


new Vue({
    el: '#app',
    router: router,
    store: store,
    render: h => h(App)
});