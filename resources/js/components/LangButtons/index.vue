<template>
  <el-select
    v-model="value"
    value-key="value"
    @change="handleSetLanguage"
    :popper-class="theme"
    class="international-select"
  >
    <template slot="prefix">
      <img class="prefix international__img" :src="value.img" />
    </template>
    <el-option
      v-for="item in options"
      :key="item.value"
      :label="item.label"
      :value="item"
      class="international-select__option"
    >
      <img :src="item.img" class="international__img" /> {{ item.label }}
    </el-option>
  </el-select>
</template>

<script>
import { setLang } from "@/api/user";
export default {
  
  name: "LangButtons",
  props: {
    theme: {
      type: String,
      default: "default"
    }
  },
  data() {
    return {
      options: [
        {
          value: "en",
          label: "ENG",
          img: "https://www.worldometers.info/img/flags/us-flag.gif"
        },
        {
          value: "ru",
          label: "RUS",
          img: "https://www.worldometers.info/img/flags/rs-flag.gif"
        },
        {
          value: "es",
          label: "ESP",
          img: "https://www.worldometers.info/img/flags/sp-flag.gif"
        }
      ],
      value: null
    };
  },

  created() {
    this.value = this.options.find(i => i.value === this.language);
  },
  computed: {
    token() {
      return this.$store.getters.token;
    },
    language() {
      return this.$store.getters.language;
    }
  },
  methods: {
    async handleSetLanguage() {
      const lang = this.value.value || this.options[0].value;
      if(this.token){
        await setLang(lang);
      }
      this.$i18n.locale = lang;
      this.$store.dispatch("app/setLanguage", lang);
      // this.$message({
      //   message: "Switch Language Success",
      //   type: "success"
      // });
    }
  }
};
</script>

<style lang="scss">
.international {
  &__img {
    width: 22px;
    height: 14px;
    margin-right: 6px;
  }

  &-prefix {
    top: 6px;
  }

  &-select {
    width: 58px;
    height: 26px;
    margin: 0 15px 0 auto;
    padding: 0;

    .el-input__prefix {
      top: 4px;
    }

    .el-input__inner {
      height: 26px;
      padding: 6px;
      font-size: 10px;
      line-height: 12px;
      text-align: right;
    }

    .el-input__suffix {
      display: none;
    }

    &__option {
      width: 58px;
      height: 22px;
      padding: 0 0 0 6px;
      display: flex;
      align-items: center;
      font-size: 10px;
      line-height: 12px;

      &:not(:last-of-type) {
        margin-bottom: 12px;
      }
    }
  }
}
</style>
