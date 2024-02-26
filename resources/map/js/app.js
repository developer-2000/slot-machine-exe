import Vue from "vue";
import App from "./components/App";
import i18n from "./lang";
// import "./components";
// Vue.component("Test", require("./components/Test.vue"));
Vue.config.productionTip = false;
new Vue({
  i18n,
  ...App,
});
