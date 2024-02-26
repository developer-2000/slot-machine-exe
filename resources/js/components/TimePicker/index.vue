<template>
  <el-time-picker
    is-range
    :value="value"
    range-separator="To"
    start-placeholder="Start time"
    end-placeholder="End time">
  </el-time-picker>
</template>

<script>

  export default {
    props:['value'],
    name: 'TimePicker',
    data() {
      return {
        value1: [new Date(2021, 9, 10, 8, 40), new Date(2021, 9, 10, 9, 40)],
      };
    }
  }
</script>

<style>

</style>
