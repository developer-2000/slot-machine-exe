import vue from 'vue'
import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import i18n from "./lang";
import '@/styles/index.scss' // global css

import App from './App.vue'
import store from './store'
import router from './router/index'

import '@/icons' // icon
import '@/permission' // permission control

/**
 * If you don't want to use mock-server
 * you want to use MockJs for mock api
 * you can execute: mockXHR()
 *
 * Currently MockJs will be used in the production environment,
 * please remove it before going online ! ! !
 */

// set ElementUI lang to EN
vue.use(ElementUI, {
  i18n: (key, value) => i18n.t(key, value)
});
// 如果想要中文版 element-ui，按如下方式声明
// vue.use(ElementUI)
// Object.keys(filters).forEach((key) => {
//   vue.filter(key, filters[key]);
// });
vue.config.productionTip = false

new vue({
  el: '#app',
  router,
  store,
  i18n,
  render: h => h(App)
})
