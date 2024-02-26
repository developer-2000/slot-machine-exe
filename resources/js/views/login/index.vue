<template>
  <div class="login-container">
    <img
      class="login-container__logo"
      :src="require('@/assets/logo/logo-big.png').default"
    />

    <lang-buttons class="login-lang" :theme="theme" />

    <el-form
      ref="loginForm"
      :model="loginForm"
      :rules="loginRules"
      class="login-form"
      auto-complete="on"
      label-position="left"
    >
      <div class="title-container">
        <h3 class="title">{{ $t("login.title") }}</h3>
      </div>

      <div class="login-form__label">{{ $t("login.email") }}</div>
      <el-form-item prop="email" class="el-form-item--email">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input
          ref="email"
          v-model="loginForm.email"
          placeholder="@"
          name="email"
          type="email"
          tabindex="1"
          auto-complete="on"
        />
      </el-form-item>

      <div class="login-form__label">{{ $t("login.password") }}</div>
      <el-form-item prop="password" class="el-form-item--password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :key="passwordType"
          ref="password"
          v-model="loginForm.password"
          :type="passwordType"
          name="password"
          tabindex="2"
          auto-complete="on"
          @keyup.enter.native="handleLogin"
        />
        <span class="show-pwd" @click="showPwd">
          <svg-icon
            :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'"
          />
        </span>
      </el-form-item>

      <el-button
        :loading="loading"
        class="login-submit"
        type="primary"
        style="width: 100%; margin-bottom: 30px"
        @click.native.prevent="handleLogin"
      >
        {{ $t("login.logIn") }}</el-button
      >
    </el-form>
  </div>
</template>

<script>
import { validEmail } from "@/utils/validate";
import LangButtons from "@/components/LangButtons";

export default {
  name: "Login",
  components: {
    LangButtons,
  },
  data() {
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error(this.$t("form.corr")));
      } else {
        callback();
      }
    };
    const validatePassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error(this.$t("form.corr")));
      } else {
        callback();
      }
    };
    return {
      loginForm: {
        email: "",
        password: "",
      },
      loginRules: {
        email: [{ required: true, trigger: "blur", validator: validateEmail }],
        password: [
          { required: true, trigger: "blur", validator: validatePassword },
        ],
      },
      loading: false,
      passwordType: "password",
      redirect: undefined,
      theme: "login-lang__dropdown dark",
      otherQuery: {},
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        const query = route.query;
        if (query) {
          this.redirect = query.redirect;
          this.otherQuery = this.getOtherQuery(query);
        }
      },
      immediate: true,
    },
  },
  methods: {
    showPwd() {
      if (this.passwordType === "password") {
        this.passwordType = "";
      } else {
        this.passwordType = "password";
      }
      this.$nextTick(() => {
        this.$refs.password.focus();
      });
    },
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== "redirect") {
          acc[cur] = query[cur];
        }
        return acc;
      }, {});
    },
    handleLogin() {
      this.$refs.loginForm.validate((valid) => {
        if (valid) {
          this.loading = true;
          this.$store
            .dispatch("user/login", this.loginForm)
            .then(() => {
              this.$router.push({
                path: this.redirect || "/profile",
                query: this.otherQuery,
              });
              this.loading = false;
            })
            .catch((error) => {
              if (
                error &&
                error.response &&
                error.response.data &&
                error.response.data.errors
              ) {
                [...(Object.keys(error.response.data.errors) || [])].forEach(
                  (i) => {
                    this.$message({
                      message: error.response.data.errors[i][0],
                      type: "error",
                    });
                  }
                );
              } else if (typeof error === "string") {
                this.$message({
                  message: error,
                  type: "error",
                });
              } else {
                this.$message({
                  message: "Login failed",
                  type: "error",
                });
              }

              this.loading = false;
            });
        } else {
          return false;
        }
      });
    },
  },
};
</script>

<style lang="scss">
/* 修复input 背景不协调 和光标变色 */
/* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

$bg: #283443;
$light_gray: #fff;
$cursor: #fff;
$error-color: #ff0000;

/* reset element-ui css */
.login {
  &-form {
    .el-input {
      height: 47px;
      width: 85%;
      display: inline-block;

      input {
        height: 47px;
        padding: 12px 5px 12px 15px;
        background: transparent;
        border: 0;
        border-radius: 0;
        color: $light_gray;
        caret-color: $cursor;
        -webkit-appearance: none;

        &:-webkit-autofill {
          width: 278px;
          box-shadow: 0 0 0 1000px $bg inset !important;
          -webkit-text-fill-color: $cursor !important;
        }
      }
    }

    .el-form-item {
      background: rgba(0, 0, 0, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 3px;
      color: #fff;

      &.is-error {
        border: 1px solid $error-color;
      }

      &--email {
        margin-bottom: 20px;
      }

      &--password {
        margin-bottom: 20px;
      }
    }
  }

  &-lang {
    margin: 20px 20px 0 auto;
    display: block;
    border-radius: 3px;

    .el-input {
      &__prefix {
        top: 6px;
        width: 22px;
        height: 14px;
      }

      &__inner {
        background: linear-gradient(180deg, #07272d 0%, #062328 100%);
        border: none;
        color: #fff;
      }
    }

    &__dropdown {
      background: linear-gradient(180deg, #07272d 0%, #062328 100%);
      border: none;

      .el-select-dropdown__item {
        color: #fff;

        &.selected {
          color: #fff;
        }

        &.hover,
        &:hover {
          background: transparent;
        }
      }

      .popper__arrow {
        display: none;
      }
    }
  }

  .el-form-item__error {
    color: $error-color;
  }
}
</style>

<style lang="scss" scoped>
$dark_gray: #889aa4;
$light_gray: #eee;
$brand_blue: #268597;
$brand_yellow: #ecbf24;

.login {
  &-container {
    min-height: 100%;
    width: 100%;
    overflow: hidden;
    background: url("../../assets/bg-login.jpg") center no-repeat;
    // background: url("/images/bg-login.jpg") center no-repeat;
    background-size: cover;

    &__logo {
      position: absolute;
      top: 10px;
      left: 21px;
    }

    .svg-container {
      display: none;
    }

    .title-container {
      position: relative;

      .title {
        font-size: 26px;
        color: $light_gray;
        margin: 0px auto 40px auto;
        text-align: center;
        font-weight: bold;
      }
    }

    .show-pwd {
      position: absolute;
      right: 10px;
      top: 10px;
      font-size: 16px;
      color: $dark_gray;
      cursor: pointer;
      user-select: none;

      .svg-icon {
        width: 24px;
        height: 24px;
        vertical-align: -0.3em;
      }
    }
  }

  &-form {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 300px;
    max-width: 100%;
    margin: 0 auto;
    padding: 10px;
    overflow: hidden;

    &__label {
      margin-bottom: 5px;
      color: rgba(255, 255, 255, 0.3);
    }
  }

  &-submit {
    height: 54px;
    margin-bottom: 0;
    background: $brand_yellow;
    border: $brand_yellow;
    color: #07272d;
    font-size: 20px;
    font-weight: 700;
    line-height: 22px;
    text-transform: uppercase;
  }
}
</style>
