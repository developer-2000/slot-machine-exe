<template>
  <div class="edit-attraction__container">
    <h2 class="edit-attraction__title">
      {{ $t("attraction.heading") }} {{ $route.params.id }}
    </h2>
    <el-row>
      <el-col :xs="24" :sm="10" :md="7" :lg="6" :xl="6">
        <div class="edit-attraction__info edit-attraction__info_owner">
          <h3 class="edit-attraction__heading">{{ $t("table.owner") }}</h3>
          <p v-if="user">
            {{
              `${user.first_name ? user.first_name + " " : ""}${
                user.middlename ? user.middlename + " " : ""
              }${user.surname ? user.surname + " " : ""}${user.email}` ||
                $t("table.notFilled")
            }}
          </p>
        </div>

        <div class="edit-attraction__info edit-attraction__info_location">
          <h3 class="edit-attraction__heading">
            {{ $t("attraction.location") }}
          </h3>
          <p>
            id:
            <span class="eddit-attraction__id">{{
              formAttraction.location_id
            }}</span>
          </p>
        </div>

        <el-form
          ref="ruleForm"
          :rules="rulesAttraction"
          :label-position="labelPosition"
          label-width="100px"
          :model="formAttraction"
        >
          <el-form-item
            :label="$t('table.title')"
            style="min-height:100%"
            prop="title"
          >
            <el-input
              v-model="formAttraction.title"
              class="edit-attraction__uid"
            />
          </el-form-item>
          <el-form-item prop="activation">
            <el-checkbox
              v-model="formAttraction.activation"
              class="edit-attraction__checkbox"
              >{{ $t("table.status") }}</el-checkbox
            >
          </el-form-item>
          <el-button
            class="edit-attraction__submit"
            type="primary"
            :disabled="
              title === formAttraction.title &&
                activation === formAttraction.activation
            "
            @click="submitForm('ruleForm')"
            >{{ $t("buttons.save") }}</el-button
          >
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import TimePicker from "@/components/TimePicker/index";
import FormItemAdd from "@/components/FormItemAdd/index";
import { getProfile } from "@/api/user";
import {
  getAttraction,
  updateAttraction,
  updateAttractionsActivation,
} from "@/api/attractions";
export default {
  name: "EditLocation",
  components: {
    TimePicker,
    FormItemAdd,
  },
  data() {
    return {
      labelPosition: "top",
      user: "",
      title: "",
      activation: "",
      rulesAttraction: {},
      formAttraction: {
        title: "",
        id: "",
        location_id: "",
        activation: false,
      },
    };
  },
  watch: {
    language() {
      this.setRules();
    },
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
    userId() {
      return this.$store.getters.user_id;
    },
  },
  async created() {
    this.setRules();
    try {
      if (this.$route.params.id) {
        let { data } = await getAttraction(this.$route.params.id, this.userId);
        if (data[0].attraction.user_id) {
          const user = await getProfile(data[0].attraction.user_id);
          this.user = user.data;
        }
        this.title = data[0].attraction.title;
        this.activation = !!data[0].attraction.activation;
        this.formAttraction = {
          title: data[0].attraction.title,
          id: data[0].attraction.id,
          location_id: data[0].attraction.location_id,
          activation: !!data[0].attraction.activation,
        };
      }
      this.loadingPage = false;
    } catch (error) {
      this.loadingPage = false;
    }
  },
  methods: {
    setRules() {
      this.rulesAttraction = {
        title: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.title") }),
            trigger: "blur",
          },
        ],
      };
    },
    async submitForm(formName) {
      await this.$refs[formName].validate(async (valid) => {
        if (valid) {
          try {
            if (this.formAttraction.title !== this.title) {
              const { data } = await updateAttraction(this.formAttraction.id, {
                // activation: this.formAttraction.activation ? 1 : 0,
                title: this.formAttraction.title,
                location_id: this.formAttraction.location_id,
              });
              if (data.status !== "success") {
                throw "error";
              }
            }
            if (this.formAttraction.activation !== this.activation) {
              const { data } = await updateAttractionsActivation({
                activation: this.formAttraction.activation ? 1 : 0,
                attraction_id: this.formAttraction.id,
              });
              if (data.status !== "success") {
                throw "error";
              }
            }
            this.$message({
              message:this.$t('result.attractions updated successfully'),
              type: "success",
            });
            this.$router.push(`/attractions`);
          } catch (error) {
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
                message: "Attraction editing error ",
                type: "error",
              });
            }
          }
        } else {
          return false;
        }
      });
    },
    deleteItem(index) {
      this.list.splice(index, 1);
    },
  },
};
</script>

<style lang="scss" scoped>
$brand_blue: #268597;

@mixin font($size, $weight, $line-height, $color: null) {
  color: $color;
  font-size: $size;
  font-weight: $weight;
  line-height: $line-height;
}
::v-deep .el-form-item__label {
  min-height: 100% !important;
  @include font(16px, 700, 18px);
}

.el-checkbox__input.is-checked .el-checkbox__inner {
  background: $brand_blue;
  border-color: $brand_blue;
}

.el-checkbox__input.is-checked + .el-checkbox__label {
  color: inherit;
}

.edit {
  &-attraction {
    &__title {
      margin-bottom: 40px;
    }

    &__info {
      margin-bottom: 22px;
    }

    &__checkbox {
      margin-bottom: 22px;
    }

    &__uid {
      .el-input__inner {
        -moz-appearance: textfield;

        &::-webkit-outer-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }

        &::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
      }
    }

    &__submit {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }
}
</style>
