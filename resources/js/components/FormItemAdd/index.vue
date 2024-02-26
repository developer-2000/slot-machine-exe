<template>
  <ul class="adding-list">
    <li class="adding-list__item adding-item">
      <h3 class="adding-item__title">{{ item.title }}</h3>
      <p v-if="item.id" class="adding-item__uid">
        id: <span class="uid">{{ item.id }}</span>
      </p>
      <p class="adding-item__info" v-if="item.address">
        Аddress:
        <span class="location-address">
          {{
            `${
              item.address && item.address.region
                ? item.address.region.name + ", "
                : ""
            }${
              item.address.country && item.address.country.name
                ? item.address.country.name + ", "
                : ""
            }${
              item.address.city && item.address.city.name
                ? item.address.city.name + ", "
                : ""
            }${
              item.address && item.address.street ? item.address.street : ""
            }` || $t("table.notFilled")
          }}</span
        >
      </p>
      <p class="adding-item__info" v-if="item.working_hours">
        {{$t('clients.workingHours')}}: from
        <span
          class="location-time_open"
          v-if="
            item.working_hours.open !== null &&
              item.working_hours.open !== undefined
          "
          >{{
            $d(
              item.working_hours.open * 1000 +
                new Date(0).getTimezoneOffset() * 60 * 1000,
              "time"
            )
          }}</span
        >
        to
        <span
          class="location-time_close"
          v-if="
            item.working_hours.close !== null &&
              item.working_hours.close !== undefined
          "
          >{{
            $d(
              item.working_hours.close * 1000 +
                new Date(0).getTimezoneOffset() * 60 * 1000,
              "time"
            )
          }}</span
        >
      </p>
      <el-button
        v-if="!item.isUnremovable"
        class="adding-item__delete"
        @click="deleteItem"
      ></el-button>
    </li>
  </ul>
</template>

<script>
export default {
  name: "FormItemAdd",
  data() {
    return {};
  },
  props: ["item",'index'],

  methods: {
    deleteItem() {
      this.$emit("deleteItem", this.index);
    },
  },
};
</script>

<style lang="scss">
$brand_blue: #268597;

@mixin font($size, $weight, $line-height, $color: null) {
  color: $color;
  font-size: $size;
  font-weight: $weight;
  line-height: $line-height;
}

.adding {
  &-list {
    list-style: none;
  }

  &-item {
    position: relative;
    padding: 6px 0 10px;
    border-top: 2px solid $brand_blue;

    &__title {
      margin-bottom: 4px;
      @include font(18px, 700, 21px);
    }

    &__uid {
      margin-bottom: 4px;
      @include font(10px, 400, 12px);
    }

    &__delete {
      position: absolute;
      top: 0;
      right: 0;
      width: 45px;
      height: 45px;
      background: transparent url("../../assets/btn_delete.svg") center
        no-repeat;
      border: none;
    }
  }
}
</style>
