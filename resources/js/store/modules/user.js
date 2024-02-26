import { login, logout, getInfo, signupAdmin } from "@/api/user";
import { getToken, setToken, removeToken } from '@/utils/auth'
import { resetRouter } from '@/router'
import Cookies from "js-cookie";
import store from "../index";
import router from "../../router";
const getDefaultState = () => {
  return {
    token: getToken(),
    name: '',
    user_id:'',
    roles: []
  }
}

const state = getDefaultState()

const mutations = {
  RESET_STATE: (state) => {
    Object.assign(state, getDefaultState())
  },
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_USER_ID: (state, id) => {
    state.user_id = id;
    localStorage.setItem("user_id", id);
  },
   SET_ROLES: (state, roles) => {
    state.roles = roles
  }
}

const actions = {
  // user login
  login({ commit }, userInfo) {
    const { email, password } = userInfo;
    return new Promise((resolve, reject) => {
      login({ email: email.trim(), password: password })
        .then((response) => {
          const { data } = response;
          commit("SET_TOKEN", data.token);
          commit("SET_USER_ID", data.user.id);
          commit("SET_ROLES", data.user.access);
          setToken(data.token);
          store.dispatch("permission/generateRoutes", data.user.access)
            .then((accessRoutes) => {
              router.addRoutes(accessRoutes);
               resolve();
            })
            .catch((error) => {
              reject(error);
            });
          // if (data.user.access.includes("attraction")) {
          //   Cookies.set("sidebarStatus", 0);
          // }else{
          //   Cookies.set("sidebarStatus", 1);
          // }
         
        })
        .catch((error) => {
          reject(error);
        });
    });
  },
  signUp({ commit }, userInfo) {
    return new Promise((resolve, reject) => {
      signupAdmin(userInfo)
        .then((response) => {
          // const { data } = response;
          // commit("SET_USER_ID", data.user.id);
          // commit("SET_TOKEN", data.token);
          // commit("SET_ROLES", data.user.access);
          // setToken(data.token);
          resolve();
        })
        .catch((error) => {
          reject(error);
        });
    });
  },

  // get user info
  getInfo({ commit, state }, isPer=false) {
    return new Promise((resolve, reject) => {
      getInfo({
        token: state.token,
        user_id: state.user_id
          ? state.user_id
          : localStorage.getItem("user_id"),
      })
        .then((response) => {
          const { data } = response;
          if (!data) {
            return reject("Verification failed, please Login again.");
          }
          if (isPer) {
              commit("SET_USER_ID", data.id);
              commit("SET_ROLES", data.access);
          }else{
              commit("SET_USER_ID", data.user.id);
              commit("SET_ROLES", data.user.access);
          }
        
          resolve(data);
        })
        .catch((error) => {
          reject(error);
        });
    });
  },

  // user logout
  logout({ commit, state }) {
    return new Promise((resolve, reject) => {
      logout()
        .then(() => {
          commit("SET_TOKEN", "");
          commit("SET_USER_ID", '');
          localStorage.removeItem("user_id");
          commit("SET_ROLES", []);
          removeToken(); 
          resetRouter();
          // store.dispatch("permission/SET_ROUTES", []);
          resolve();
        })
        .catch((error) => {
          reject(error);
        });
    });
  },

  // remove token
  resetToken({ commit }) {
    return new Promise((resolve) => {
      removeToken(); // must remove  token  first
      commit("RESET_STATE");
      resolve();
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

