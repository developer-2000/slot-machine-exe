<template>
  <div class="block">
    <el-date-picker
      class="date-picker"
      @change="$emit('change',$event)"
      :value="value"
       @input="$emit('input', $event)"
      type="date"
       v-bind="$attrs"
      placeholder="20.12.2021">
    </el-date-picker>
  </div>
</template>

<script>
  export default {
     inheritAttrs: false,
    props:['value'],
    name: 'DatePicker',
    data() {
      return {
      };
    }
  };
</script>

<style lang="scss">
  @import "~@/styles/variables";

  @mixin btn-style {
    background-color: $brand_blue;

    &:hover {
      background-color: $brand_darken_blue
    }

    &:active {
      background-color: #FFF;
      border: 2px solid currentColor;
      color: $brand_blue
    }
  }

  .date-picker {
    .el-input__suffix{
      padding-right: 40px!important;
    }
    .el-input__prefix {
      right: 0;
      left: auto;
      width: 40px;
      height: 40px;
      border-top-right-radius: 3px;
      border-bottom-right-radius: 3px;
      color: #FFF;
      
      @include btn-style
    }

    /*Переопределение стилей*/
    &.el-input--prefix .el-input__inner{
      padding-left: 10px;
    }
  }
</style>
