<template>
  <div>
    <el-input v-on:keyup.enter.native="$emit('search', search)"  v-model="search" :placeholder="$t('inputs.placeholderSearch')">
      <el-button
        @click="$emit('search', search)"
        class="search-submit"
        slot="append"
        icon="el-icon-search"
      ></el-button>
    </el-input>
  </div>
</template>
<style>
.input-with-search .el-input-group__prepend {
  background-color: #fff;
}
</style>
<script>
export default {
  data() {
    return {
      search: "",
      placeholder: "Поиск",
    };
  },
};
</script>

<style lang="scss">
$brand_blue: #268597;

.search {
  &-submit {
    width: 40px;
    height: 40px;
    margin: 0 !important;
    padding: 0;
  }
}

.el-input-group__append {
  position: absolute;
  top: 0;
  right: 0;
  width: 40px;
  height: 40px;
  padding: 0;
  background-color: transparent;
  border: none;
}

.el-icon-search {
  font-size: 20px;
  font-weight: 700;

  &::before {
    color: $brand_blue;
  }
}
</style>
