import './bootstrap';
import './plugins';
import Vue from 'vue';
import './core/coreApp';
import store from './store/Index'
import './common/Translator'
import './common/Helper/helpers'
import './common/commonComponent'
import './tenant/tenantComponent'
import lunarCalendar from 'vue-lunar-calendar';
// import LunarCalendar from 'vue-lunar-calendar-pro';

Vue.component("lunar-calendar", lunarCalendar);
// Vue.component("lunar-calendar", LunarCalendar);

const app = new Vue({
    store,
    el: '#app',
});
