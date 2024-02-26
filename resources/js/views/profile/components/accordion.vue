<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item name="1">
      <template slot="title">
        <!--   :on-change="handleAvatarChange" -->
        <el-upload
          class="avatar-uploader profile-attractions__img"
          method="post"
          :show-file-list="false"
          :on-change="handleAvatarChange"
          :on-success="handleAvatarSuccess"
          :headers="{ Authorization: token }"
          :action="url + '/api/location/load_avatar'"
          accept="image/jpeg,image/jpg,image/png"
          :auto-upload="false"
          :before-upload="beforeAvatarUpload"
        >
          <div class="avatar-hover">
            <i class="el-icon-plus avatar-uploader-icon" />
          </div>
          <!-- :src=" -->
          <!-- `http://ch-throw.ru/storage/app/public/${data.location.url_avatar}` -->
          <!-- " -->
          <img
            v-if="imageUrl"
            :src="imageUrl"
            class="avatar"
            width="74"
            height="74"
          />

          <!--todo: по дефолту - вот это изображение default-logo.png (тут вставила через base64, тк не подгружаются картинки через путь к ней, и чтобы проверить)-->
          <img
            v-else
            class="avatar-uploader-icon"
            src="data:image/png;base64,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"
            width="74"
            height="74"
          />
        </el-upload>

        <div class="profile-attractions__brief">
          <h2 class="profile-attractions__location">{{ data.title }}</h2>

          <p class="profile-attractions__address">
            {{
              `${
                data.location.region && data.location.region.name
                  ? data.location.region.name
                  : ""
              }${
                data.location.country && data.location.country.name
                  ? ", " + data.location.country.name
                  : ""
              }${
                data.location.city && data.location.city.name
                  ? ", " + data.location.city.name
                  : ""
              }${
                data.location && data.location.street
                  ? ", " +
                    `${
                      typeof data.location.street === "object" &&
                      data.location.street !== null
                        ? data.location.street.name
                        : data.location.street
                    }`
                  : ""
              }` || $t("table.notFilled")
            }}
          </p>
        </div>
      </template>

      <div class="grid-content profile-license-payment">
        <h2 class="profile__subtitle">{{ $t("profile.licenseHeading") }}</h2>

        <ul class="profile__col profile-col">
          <li class="profile-col__item profile-item profile-item--licence">
            <h3 class="profile-item__title">{{ $t("profile.licStatus") }}:</h3>
            <p class="profile-col__value">
              {{ data.payment_state ? "Активна" : "Не активна" }}
            </p>
          </li>

          <li class="profile-col__item profile-item profile-item--licence">
            <h3 class="profile-item__title">{{ $t("profile.paydate") }}:</h3>
            <p class="profile-col__value">
              {{ data.payment_date || $t("table.notFilled") }}
            </p>
          </li>

          <li class="profile-col__item profile-item profile-item--licence">
            <h3 class="profile-item__title">{{ $t("profile.amuseQty") }}:</h3>
            <p class="profile-col__value">{{ data.count_attractions }}</p>
          </li>

          <li class="profile-col__item profile-item profile-item--licence">
            <h3 class="profile-item__title">
              {{ $t("profile.licenseCost") }}:
            </h3>
            <p class="profile-col__value">{{ data.license_cost }}</p>
          </li>

          <!-- <li class="profile-col__item profile-item profile-item--licence">
            <h3 class="profile-item__title">{{ $t("profile.card") }}:</h3>
            <p class="profile-col__value">1234*******8900</p>
            <a href="#" class="profile-item__card-binding">{{
              $t("buttons.binding")
            }}</a>
          </li> -->

          <li
            class="profile-col__item profile-item profile-item--licence profile-item--licence-autopay"
          >
            <el-checkbox
              v-if="false"
              v-model="checkedAcc"
              class="profile__autopayment profile-item__title"
              >{{ $t("buttons.autopayment") }}</el-checkbox
            >

            <el-button type="primary" @click="onPay" class="profile__pay">{{
              $t("buttons.pay")
            }}</el-button>
          </li>
        </ul>
      </div>
      <div
        v-for="attraction in data.location.attractions"
        :key="attraction.id"
        class="profile-attractions__item profile-attraction"
      >
        <h3 class="profile-attraction__title">{{ attraction.title }}</h3>
        <p class="profile-attraction__uid">
          id: <span class="uid">{{ attraction.id }}</span>
        </p>
        <p
          v-if="attraction.activation === 1"
          class="profile-attraction__status profile-attraction__status_accepted"
        >
          {{ $t("table.status") }}:
          <span>{{ $t("profile.status.approved") }}</span>
        </p>
        <!-- <p
          v-else-if="index === 3"
          class="profile-attraction__status profile-attraction__status_processing"
        >
          {{ $t("table.status") }}:
          <span>{{ $t("profile.status.processing") }}</span>
        </p> -->
        <p
          v-if="attraction.activation === 0"
          class="profile-attraction__status profile-attraction__status_cancelled"
        >
          {{ $t("table.status") }}:
          <span>{{ $t("profile.status.reject") }}</span>
        </p>
      </div>
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import axios from "axios";
// import { payment } from "@/api/user";
export default {
  name: "Accordion",
  props: ["checked", "data", "admin_id"],
  data() {
    return {
      activeName: "1",
      url: process.env.MIX_APP_URL,
      checkedAcc: this.checked,
      imageUrl:
        this.data && this.data.location && this.data.location.url_avatar
          ? process.env.MIX_APP_URL +
            "storage/app/public/" +
            this.data.location.url_avatar
          : "",
    };
  },
  computed: {
    token() {
      return this.$store.getters.token;
    },
  },
  methods: {
    async onPaymalPayment(value) {
      try {
        this.listLoading = true;
        axios({
          maxRedirects: 0,
          method: "get",
          headers: {
            Accept: "application/json",
            // "Accept-Language": "en_US",
            // Authorization: "Bearer " + this.token,
            "Content-Type": "application/x-www-form-urlencoded",
            "Access-Control-Allow-Origin": "*",
          },
          url: process.env.MIX_APP_URL + "paypal/payment",
          params: value,
        })

          .then((res) => {
            if (res.data && res.data.status === "error") {
              this.$message.error(res.data.message);
            } else {
               window.location = res.data.message.link
              // let textDoc = document.createElement("a");
              // textDoc.target = "_blank";
              // textDoc.href = res.data.message.link;
              // textDoc.click();
              // textDoc.remove();
              // textDoc = null;
            }
          })
          .catch((err) => {
            this.$message.error("error");
            console.log("err", err);
          });
        // const { data } = await payment(value);
        // const { data } = await payment(value);
        this.listLoading = false;
      } catch (error) {
        console.log("error", error);
      }
    },
    async handleAvatarChange(file) {
      const isJPG = file.raw.type === "image/jpeg";
      const isPNG = file.raw.type === "image/png";
      const isLt2M = file.raw.size / 1024 / 1024 < 5;
      if (!isPNG && !isJPG) {
        this.$message.error(
          "Upload avatar image can only be in JPG/PNG format!"
        );
        return false;
      } else if (!isLt2M) {
        this.$message.error(
          "The size of the uploaded avatar image cannot exceed 5mb!"
        );
        return false;
      } else if (isLt2M && (isPNG || isJPG)) {
        const formData = new FormData();
        formData.append("avatar", file.raw);
        formData.append("location_id", this.data.location.id);
        let { data } = await axios.post(
          `${this.url}api/location/load_avatar`,
          formData,
          {
            headers: {
              Authorization: "Bearer " + this.token,
              "Content-Type": "multipart/form-data",
            },
          }
        );
        if (data.status === "success") {
          this.imageUrl =
            this.url +
            "storage/app/public/" +
            data.message.path_physically +
            "?" +
            new Date().getTime();
          this.$message({
            message: "Logo installed successfully",
            type: "success",
          });
        }
        this.fileChange = false;
        return isLt2M && (isPNG || isJPG);
      }
    },

    async onPay() {
      await this.onPaymalPayment({
        admin_id: this.admin_id,
        location_id: this.data.location.id,
      });
    },
    handleAvatarSuccess(res, file) {
      this.imageUrl = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("Avatar picture must be JPG format!");
      }
      if (!isLt2M) {
        this.$message.error("Avatar picture size can not exceed 2MB!");
      }
      return isJPG && isLt2M;
    },
  },
};
</script>

<style lang="scss">
.avatar-uploader:hover {
  .avatar-hover {
    display: block;
  }
}
.avatar-hover {
  z-index: 20;
  display: none;
  color: #fff;
  position: absolute;
  background: rgba(84, 84, 84, 0.15);
  width: 74px;
  height: 74px;
  top: 0;
  left: 0;
  font-size: 37px;
  .el-icon-plus {
    &::before {
      margin-top: 20px;
      display: block;
    }
  }
}
.el-collapse-item__arrow {
  top: 24px;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  transform: rotate(90deg);

  &.is-active {
    transform: rotate(270deg) !important;
  }
}
</style>
