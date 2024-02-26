<template>
  <div class="profile-container">
    <el-row class="profile-container__row">
      <el-col
        :xs="24"
        :sm="24"
        :md="24"
        :lg="24"
        :xl="24"
        class="profile-container__col profile-container__col--personal"
      >
        <h2 class="profile__title">
          {{ $t("profile.personalDataHeading") }}
        </h2>

        <ul class="profile__col profile-col" v-if="user && user.email">
          <li class="profile-col__item profile-item profile-item--personal">
            <h3 class="profile-item__title">{{ $t("table.email") }}:</h3>

            <p class="profile-item__value">{{ user.email }}</p>
          </li>

          <li class="profile-col__item profile-item profile-item--personal">
            <h3 class="profile-item__title">{{ $t("table.firstname") }}:</h3>

            <p class="profile-item__value">{{ user.nickname }}</p>
          </li>

          <li class="profile-col__item profile-item profile-item--personal">
            <h3 class="profile-item__title">{{ $t("table.middlename") }}:</h3>

            <p class="profile-item__value">
              {{ user.middlename || $t("table.notFilled") }}
            </p>
          </li>

          <li class="profile-col__item profile-item profile-item--personal">
            <h3 class="profile-item__title">{{ $t("table.surname") }}:</h3>

            <p class="profile-item__value">
              {{ user.surname || $t("table.notFilled") }}
            </p>
          </li>

          <li class="profile-col__item profile-item profile-item--personal">
            <h3 class="profile-item__title">{{ $t("profile.company") }}:</h3>

            <p class="profile-item__value">
              {{ user.company || $t("table.notFilled") }}
            </p>
          </li>

          <li class="profile-col__item profile-item profile-item--personal">
            <h3 class="profile-item__title">{{ $t("table.tel") }}:</h3>

            <p class="profile-item__value">
              {{ user.tell || $t("table.notFilled") }}
            </p>
          </li>
        </ul>
      </el-col>

      <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
        <div class="grid-content">
          <h3 v-if="licenses.length===0">{{$t('form.noloc')}} </h3>
          <accordion
            v-for="license in licenses"
            :key="license.id"
            :checked="checked"
            :admin_id="user.id"
            :data="license"
          />
        </div>
      </el-col>

      <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24"> </el-col>
    </el-row>
  </div>
</template>

<script>
// import personalDataList from "@/views/profile/personalDataList";
// import licenses from "@/views/profile/licenses";
import ResizeMixin from "@/layout/mixin/ResizeHandler";
import Accordion from "./components/accordion";
import { getProfile } from "@/api/user";
export default {
  name: "Profile",
  components: { Accordion },
  mixins: [ResizeMixin],
  data() {
    return {
      // personalDataList,
      // licenses,
      user: {},
      licenses: [],
      activeName: "1",
      checked: false,
      imageUrl: "",
    };
  },
  async created() {
    await this.onGetProfile();
  },
  methods: {
    async onGetProfile() {
      try {
        this.listLoading = true;
        if(this.roles.includes('developer')){
           const { data } = await getProfile(this.userId);
        this.user = data;
        // this.licenses = data.licenses;
        }else{
           const { data } = await getProfile();
        this.user = data.message?.user;
        this.licenses = data.message?.licenses;
        }
       
        this.listLoading = false;
      } catch (error) {
        console.log(error);
      }
    },
  },
  computed: {
    device() {
      return this.$store.state.app.device;
    },
    roles() {
      return this.$store.getters.roles;
    },
    userId() {
      return this.$store.getters.user_id;
    },
  },
};
</script>

<style lang="scss">
$brand_blue: #268597;

.grid-content {
  min-height: 36px;
}

.profile-container * {
  margin: 0;
  padding: 0;
}

ul {
  list-style: none;
}

.avatar-uploader .el-upload {
  min-width: 74px;
  height: 74px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.avatar-uploader .el-upload:hover {
  border-color: $brand_blue;
}

.avatar-uploader-icon {
  max-width: 74px;
  height: 74px;
  object-fit: cover;
}

.avatar {
  width: 74px;
  height: 74px;
  display: block;
}
</style>

<style lang="scss" scoped>
$margin-list: 10px;
$border-style: 1px solid rgba(175, 187, 203, 0.4);
$brand-violet: #383d74;
$brand_blue: #268597;
$brand_blue_hover: #116272;
$color-grey: #707279;
$main-font-color: #1a1c22;
$brand-green: #0aa813;
$brand-red: #ff0000;
$brand-light-grey: #90a1bf;
$font-size: 14px;
$font-weight: 400;
$line-height: 16px;

@mixin font-style {
  font-size: $font-size;
  font-weight: $font-weight;
  line-height: $line-height;
}

@mixin font($size, $weight, $line-height, $color: null) {
  color: $color;
  font-size: $size;
  font-weight: $weight;
  line-height: $line-height;
}

::v-deep .el-collapse-item {
  &__header {
    position: relative;
    height: auto !important;
    padding: 10px;
    display: flex !important;
    border-top: $border-style;
    line-height: inherit !important;
  }

  &__arrow {
    position: absolute;
    top: 15px;
    right: 20px;
  }

  &__content {
    padding: 20px 30px 0;
    background: rgba(175, 187, 203, 0.1);
  }
}

::v-deep .el-collapse {
  border-top: none;
  border-bottom: none;
}

::v-deep .el-checkbox__inner {
  width: 19px;
  height: 19px;
  margin-right: 10px;

  &::after {
    top: 2px;
    left: 7px;
    height: 10px;
  }
}

::v-deep .international-select {
  margin-left: auto;
  display: block;
}

::v-deep .profile {
  &-container {
    padding-top: 22px;
    color: $main-font-color;

    &__col {
      &--personal {
        margin-bottom: 27px;
      }
    }

    &__header {
      display: flex;
    }

    &__img {
      max-width: 100%;
      height: auto;
    }
  }

  &__title {
    margin: 20px 0;

    @include font(24px, 700, 28px, #1a1c22);
  }

  &__subtitle {
    margin-bottom: 15px;

    @include font(16px, 700, 19px, #1a1c22);
  }

  &-col {
    @include font-style;

    &__value {
      margin-right: 5px;
      display: inline-block;
    }
  }

  &-item {
    position: relative;
    display: flex;

    @media screen and (max-width: 576px) {
      flex-direction: column;
    }

    &:not(:last-of-type) {
      margin-bottom: $margin-list;
    }

    &__title {
      
      min-width: 130px;

      @include font(inherit, 400, inherit, $color-grey);
    }

    &--personal {
      .profile-item__title {
        flex: 0 0 7%;
      }
    }

    &--licence {
      .profile-item__title {
        flex: 0 0 12%;
      }

      &-autopay {
        align-items: center;

        @media screen and (max-width: 576px) {
          align-items: flex-start;
        }
      }
    }

    &__card-binding {
      color: $brand_blue;
      text-decoration: underline;
    }
  }

  &__autopayment {
    display: block;

    @media screen and (max-width: 576px) {
      margin-bottom: 10px;
    }

    &.is-checked {
      ::v-deep .el-checkbox__label {
        color: inherit;
      }

      ::v-deep .el-checkbox__inner {
        background-color: $brand-violet;
        border-color: $brand-violet;
      }
    }
  }

  &__pay {
    height: 41px;
    width: 160px;
    background-color: $brand-blue;
    border-radius: 3px;
    text-transform: uppercase;
    transition: background-color 0.2s ease-in;

    @include font(14px, 400, 41px, #fff);

    &:hover {
      background-color: $brand_blue_hover;
      color: #fff !important;
    }
  }

  &-license-payment {
    margin-bottom: 19px;
  }

  &-attractions {
    &__img {
      min-width: 74px;
      width: 74px;
      height: 74px;
      margin-right: 20px !important;
    }

    &__info {
      width: 100%;
    }

    &__brief {
      padding-right: 60px;
    }

    &__location {
      margin-bottom: 11px !important;

      @include font(18px, 700, 23px);
    }

    &__address {
      margin-bottom: 5px;
      @include font(13px, 400, 15px);
    }

    &__qty {
      &-list {
        width: 100%;
        display: flex;
      }

      &-item {
        position: relative;
        max-width: calc(100% / 3);
        width: 100%;
        padding-left: 14px;
        display: flex;
        align-items: center;

        &:not(:last-of-type) {
          margin-right: 5px;
        }

        &:before {
          position: absolute;
          top: 0;
          left: 0;
          content: "";
          width: 11px;
          height: 13px;
          display: block;
          background-size: cover;
        }
      }
    }
  }

  &-attraction {
    padding: 15px 0 !important;
    display: flex;
    align-items: center;
    border-bottom: $border-style;
    @include font(14px, 500, 16px);

    @media screen and (max-width: 576px) {
      flex-direction: column;
      align-items: flex-start;
    }

    &__title {
      white-space: nowrap;
padding-right: 3px;
overflow: hidden;
text-overflow: ellipsis;
      min-width: 130px;
      margin-bottom: 4px;
      flex: 0 0 12%;

      @include font(14px, 500, 16px);
    }

    &__uid {
      margin-right: 33px !important;
    }
    &__uid,
    &__status {
      color: $color-grey;

      span {
        color: $main-font-color;
      }
    }

    &__status {
      &_accepted {
        span {
          color: $brand-green;
        }
      }

      &_processing {
        span {
          color: $brand-light-grey;
        }
      }

      &_cancelled {
        span {
          color: $brand-red;
        }
      }
    }
  }
}
</style>
