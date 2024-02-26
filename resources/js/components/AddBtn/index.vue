<template>
  <router-link tag="el-link" class="btn-add" type="primary" :to="to">
    {{ $t("buttons.add") }}</router-link
  >
</template>

<script>
export default {
  name: "AddBtn",
  props: ["to"],
};
</script>

<style lang="scss">
@import "~@/styles/variables";
@import "~@/styles/mixin";

.btn-add {
  width: 160px;
  height: 30px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 3px;
  color: #fff !important;
  transition: background 0.3s ease-in, color 0.3s ease-in;

  &.el-link.is-underline:hover:after {
    content: unset;
  }

  @include btn-style;
}
</style>
