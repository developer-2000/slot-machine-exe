<template>
  <div class="edit-location__container">
    <h2 class="edit-location__title">
      {{ $t("location.heading") }} ID {{ $route.params.id }}
    </h2>
    <el-row :gutter="20" v-if="!loadingPage">
      <el-col :xs="24" :sm="10" :md="10" :lg="7" :xl="6">
        <el-form
          :rules="rulesLocation"
          :label-position="labelPosition"
          label-width="100px"
          ref="formLocation"
          :model="formLabelAlign"
        >
          <el-form-item prop="title" :label="$t('table.title')">
            <el-input v-model="formLabelAlign.title" />
          </el-form-item>

          <el-form-item :label="$t('form.country')" prop="country">
            <el-select
              style="width:100%"
              v-model="formLabelAlign.country"
              filterable
              automatic-dropdown
              @focus="remoteMethodCountry"
              clearable
              placeholder="Please enter a keyword"
              :loading="loading"
            >
              <el-option
                v-for="item in optionsCountry"
                :key="item.name"
                :label="item.name"
                :value="item.code"
              >
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item
            prop="region"
            :label="$t('form.city')"
            v-if="formLabelAlign.country"
          >
            <el-select
              v-model="formLabelAlign.region"
              style="width:100%"
              filterable
              clearable
              reserve-keyword
              placeholder="Please enter a keyword"
              @focus="remoteMethodRegions"
              :loading="loading"
            >
              <el-option
                v-for="item in optionsRegions"
                :key="item.name"
                :label="item.name"
                :value="item.code"
              >
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item
            prop="city"
            :label="$t('form.region')"
            v-if="formLabelAlign.region"
          >
            <el-select
              v-model="formLabelAlign.city"
              style="width:100%"
              filterable
              clearable
              reserve-keyword
              @focus="remoteMethodCities"
              placeholder="Please enter a keyword"
              :loading="loading"
            >
              <el-option
                v-for="item in optionsCities"
                :key="item.code"
                :label="item.name"
                :value="item.code"
              >
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('form.price')" prop="price">
            <el-input v-model.number="formLabelAlign.price" />
          </el-form-item>
          <el-form-item :label="$t('form.latitude')" prop="latitude">
            <el-input v-model="formLabelAlign.latitude" />
          </el-form-item>
          <el-form-item :label="$t('form.longitude')" prop="longitude">
            <el-input v-model="formLabelAlign.longitude" />
          </el-form-item>
          <el-form-item :label="$t('form.street')" prop="street">
            <el-input v-model="formLabelAlign.street" />
          </el-form-item>
          <el-form-item :label="$t('clients.workingHours')">
            <div class="row-custom">
              <el-form-item prop="open">
                <el-select
                  style="width:100%"
                  v-model="formLabelAlign.open"
                  clearable
                  @change="changeSelectOpen"
                  placeholder="Start time"
                >
                  <el-option
                    v-for="(item, index) in generateTime()"
                    :key="index"
                    :label="item.label"
                    :value="item.value"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <span style="margin:0 5px">{{ $t("form.to") }}</span>
              <el-form-item prop="close">
                <el-select
                  style="width:100%"
                  v-model="formLabelAlign.close"
                  clearable
                  placeholder="End time"
                >
                  <!-- v-for="(item, index) in generateTime(formLabelAlign.open)" -->
                  <el-option
                    v-for="(item, index) in generateTime(formLabelAlign.open)"
                    :key="index"
                    :label="item.label"
                    :value="item.value"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item prop="activation">
                <el-checkbox
                  v-model="formLabelAlign.activation"
                  class="edit-attraction__checkbox"
                  >{{ $t("table.status") }}</el-checkbox
                >
              </el-form-item>
            </div>
          </el-form-item>

          <el-button
            class="new-client__submit"
            type="primary"
            @click="submitForm('formLocation')"
            >{{ $t("form.changeLocation") }}</el-button
          >
        </el-form>
      </el-col>

      <el-col
        :xs="24"
        :sm="14"
        :md="14"
        :lg="17"
        :xl="18"
        class="edit-location__col"
      >
        <div class="edit-location__attraction">
          <el-row :gutter="40">
            <!-- <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
             
            </el-col> -->

            <el-col :xs="24" :sm="24" :md="24" :lg="14" :xl="12">
              <h2 class="edit-location__title edit-location__title_location">
                {{ $t("location.add") }}
              </h2>
              <el-form
                :label-position="labelPosition"
                label-width="100px"
                :rules="rulesAttraction"
                ref="attraction"
                :model="formAtt"
              >
                <el-form-item :label="$t('table.title')" prop="title">
                  <el-input v-model="formAtt.title" />
                </el-form-item>

                <el-button
                  class="new-client__submit"
                  type="primary"
                  @click="submitForm('attraction')"
                  >{{ $t("buttons.newAttraction") }}</el-button
                >
                <el-button
                  class="new-client__submit"
                  type="primary"
                  style="margin-top:5px"
                  v-if="attractions.filter((i) => !i.isUnremovable).length"
                  @click="onSubmitLocAndAttraction"
                  >{{ $t("buttons.save") }}</el-button
                >
              </el-form>
            </el-col>

            <el-col
              :xs="24"
              :sm="24"
              :md="24"
              :lg="10"
              :xl="12"
              class="edit-location__list"
            >
              <form-item-add
                v-for="(item, index) in attractions"
                :item="item"
                :key="index"
                :index="index"
                @deleteItem="deleteItem"
              />
            </el-col>
          </el-row>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import TimePicker from "@/components/TimePicker/index";
import FormItemAdd from "@/components/FormItemAdd/index";
import {
  getLocationAndAttr,
  updateLocation,
  updateLocationActivation,
} from "@/api/locations";
import { createAttraction } from "@/api/attractions";
import { fetchCountry, fetchRegions, fetchCities } from "@/api/clients";
export default {
  name: "EditLocation",
  components: {
    TimePicker,
    FormItemAdd,
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
    userId() {
      return this.$store.getters.user_id;
    },
  },
  data() {
    return {
      optionsCountry: [],
      loadingPage: true,
      optionsRegions: [],
      optionsCities: [],
      attractions: [],
      startTime: "",
      endTime: "",
      loading: false,
      labelPosition: "left",
      formAtt: { title: "" },
      formLabelAlign: {
        title: "",
        country: "",
        activation: false,
        region: "",
        city: "",
        price: "",
        latitude: "",
        longitude: "",
        street: "",
        close: "",
        open: "",
      },
      activation: false,
      rulesAttraction: {},
      rulesLocation: {},
      checked: true,
    };
  },
  async created() {
    try {
      this.setRules();
      if (this.$route.params.id) {
        let { data } = await getLocationAndAttr(this.$route.params.id);
        this.optionsCountry = [{ ...data.message.country, isFirst: true }];
        this.optionsRegions = [{ ...data.message.region, isFirst: true }];
        this.optionsCities = [{ ...data.message.city, isFirst: true }];
        this.activation = data.message.activation ? true : false;
        const hours = data.message.working_hours
          ? data.message.working_hours
          : {};
        this.formLabelAlign = {
          title: data.message.title,
          city: data.message.city.code,
          activation: !!data.message.activation,
          region: data.message.region.code,
          country: data.message.country.code,
          id: data.message.id,
          price: data.message.price,
          latitude: data.message.street?.latitude || "",
          open: hours.open ?? "",
          close: hours.close ?? "",
          longitude: data.message.street?.longitude || "",
          street:
            typeof data.message.street === "object" &&
            data.message.street !== null
              ? data.message.street.name
              : data.message.street,
        };
        //  await this.remoteMethodCountry()

        this.attractions = [
          ...data.message.attractions.map((i) => ({
            ...i,
            isUnremovable: true,
          })),
        ];
      }
      this.loadingPage = false;
    } catch (error) {
      console.log(error);
      this.loadingPage = false;
    }
  },
  watch: {
    language() {
      this.setRules();
    },
    "formLabelAlign.country": {
      handler(n, o) {
        if (!this.optionsCountry.filter((i) => i.isFirst).length) {
          this.formLabelAlign.region = "";
          this.remoteMethodRegions(true);
        }
      },
    },
    "formLabelAlign.region": {
      handler(n, o) {
        if (!this.optionsRegions.filter((i) => i.isFirst).length) {
          this.formLabelAlign.city = "";
          this.remoteMethodCities(true);
        }
      },
    },
  },
  methods: {
    validateTitle(rule, value, callback) {
      if (this.attractions.filter((i) => i.title === value).length) {
        callback(new Error(this.$t("form.addedAttraction")));
      } else {
        callback();
      }
    },
    setRules() {
      this.rulesAttraction = {
        title: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.title") }),
            trigger: "blur",
          },
          { validator: this.validateTitle, trigger: "blur" },
        ],
      };
      this.rulesLocation = {
        title: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.title") }),
            trigger: "blur",
          },
        ],
        country: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.country") }),
            trigger: "blur",
          },
        ],
        region: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.region") }),
            trigger: "blur",
          },
        ],

        city: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.city") }),
            trigger: "blur",
          },
        ],
        price: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.price") }),
            trigger: "blur",
          },
          {
            type: "number",
            message: this.$t("form.num", { f: this.$t("form.price") }),
          },
        ],
        latitude: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.latitude") }),
            pattern: /^(-?\d+(\.\d+)?)\.\s*(-?\d+(\.\d+)?)$/,
            trigger: "blur",
          },
        ],
        longitude: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.longitude") }),
            pattern: /^(-?\d+(\.\d+)?)\.\s*(-?\d+(\.\d+)?)$/,
            trigger: "blur",
          },
        ],
        street: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("form.street") }),
            trigger: "blur",
          },
        ],
        close: [
          {
            required: true,
            message: this.$t("form.req", {
              f: this.$t("clients.workingHours"),
            }),
            trigger: "blur",
          },
        ],
        open: [
          {
            required: true,
            message: this.$t("form.req", {
              f: this.$t("clients.workingHours"),
            }),
            trigger: "blur",
          },
        ],
      };
    },
    changeSelectOpen(open) {
      if (this.formLabelAlign.close <= open) {
        this.formLabelAlign.close = "";
      }
    },
    generateTime(sec, allMin = 1440, step = 15) {
      const start = new Date(0);
      start.setHours(0, 0, 0, 0);
      let offset = 0;
      if (sec) {
        start.setHours(0, 0, sec, 0);
        offset = (start.getHours() * 60 + start.getMinutes()) / step;
      } else {
        start.setHours(0, -step, 0, 0);
      }
      return Array.from(Array(allMin / step - offset)).map((i) => {
        return {
          label: this.$d(
            start.setMinutes(start.getMinutes() + step) && start,
            "time"
          ),
          value:
            (start.getTime() - start.getTimezoneOffset() * 60 * 1000) / 1000,
        };
      });
    },
    deleteItem(index) {
      this.attractions.splice(index, 1);
    },
    async onSubmitLocAndAttraction() {
      try {
        let idLocation = this.formLabelAlign.id;
        if (idLocation) {
          for (const attraction of this.attractions.filter(
            (i) => !i.isUnremovable
          )) {
            await createAttraction({
              user_id: this.userId,
              location_id: idLocation,
              title: attraction.title,
            });
          }
          this.attractions = this.attractions.map((i) => ({
            ...i,
            isUnremovable: true,
          }));
          this.$message({
            message: this.$t("result.attractions added successfully"),
            type: "success",
          });
        } else {
          throw "unknown id";
        }
      } catch (error) {
        if (
          error &&
          error.response &&
          error.response.data &&
          error.response.data.errors
        ) {
          [...(Object.keys(error.response.data.errors) || [])].forEach((i) => {
            this.$message({
              message: error.response.data.errors[i][0],
              type: "error",
            });
          });
        } else if (typeof error === "string") {
          this.$message({
            message: error,
            type: "error",
          });
        } else {
          this.$message({
            message: "Attraction creation error ",
            type: "error",
          });
        }
      }
      //
    },
    async remoteMethodCountry() {
      try {
        if (
          this.optionsCountry.length === 0 ||
          this.optionsCountry.filter((i) => i.isFirst).length
        ) {
          this.loading = true;
          const { data } = await fetchCountry();
          this.optionsCountry = data.countries;
          this.loading = false;
        }
      } catch (error) {
        this.loading = false;
        this.$message({
          message: "Error getting countries",
          type: "error",
        });
      }
    },
    async remoteMethodRegions(newValue = false) {
      try {
        if (
          (this.optionsRegions.length === 0 || newValue) &&
          this.formLabelAlign.country
        ) {
          this.loading = true;
          const { data } = await fetchRegions(this.formLabelAlign.country);
          this.optionsRegions = data.regions;
          this.loading = false;
        }
      } catch (error) {
        this.$message({
          message: "Error getting state",
          type: "error",
        });
        this.loading = false;
      }
    },
    async remoteMethodCities(newValue = false) {
      try {
        if (this.formLabelAlign.region !== "" && this.optionsCities.length) {
          this.loading = true;
          const { data } = await fetchCities(this.formLabelAlign.region);
          this.optionsCities = data.cities;
          this.loading = false;
        }
      } catch (error) {
        this.$message({
          message: "Error getting cities",
          type: "error",
        });
        this.loading = false;
      }
    },
    async submitForm(formName) {
      await this.$refs[formName].validate(async (valid) => {
        if (valid) {
          if (formName === "formLocation") {
            try {
              const activation = this.formLabelAlign.activation;
              delete this.formLabelAlign.activation;
              if (activation !== this.activation) {
                await updateLocationActivation({
                  location_id: this.formLabelAlign.id,
                  activation: activation ? 1 : 0,
                });
              }
              let location = {
                title: this.formLabelAlign.title,
                country: JSON.stringify(
                  this.optionsCountry.find(
                    (i) => i.code === this.formLabelAlign.country
                  )
                ),
                city: JSON.stringify(
                  this.optionsCities.find(
                    (i) => i.code === this.formLabelAlign.city
                  )
                ),
                region: JSON.stringify(
                  this.optionsRegions.find(
                    (i) => i.code === this.formLabelAlign.region
                  )
                ),
                street: this.formLabelAlign.street,
                activation: this.formLabelAlign.activation ? 1 : 0,
                working_hours: JSON.stringify({
                  open: this.formLabelAlign.open,
                  close: this.formLabelAlign.close,
                }),
                price: this.formLabelAlign.price,
                street: JSON.stringify({
                  name: this.formLabelAlign.street,
                  latitude: this.formLabelAlign.latitude,
                  longitude: this.formLabelAlign.longitude,
                }),
              };
              await updateLocation(this.formLabelAlign.id, location);
              this.$message({
                message: this.$t("result.location updated"),
                type: "success",
              });
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
                  message: "Attraction creation error ",
                  type: "error",
                });
              }
            }
          }
          if (formName === "attraction") {
            this.attractions.unshift({ ...this.formAtt });
            this.formAtt.title = "";
          }
        } else {
          return false;
        }
      });
    },
  },
};
</script>

<style lang="scss">
@import "~@/styles/variables";

@mixin font($size, $weight, $line-height, $color: null) {
  color: $color;
  font-size: $size;
  font-weight: $weight;
  line-height: $line-height;
}

.el-form-item__label {
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
  &-location {
    &__title {
      margin-bottom: 40px;
    }

    &__col {
      padding-top: 28px;
    }

    &__checkbox {
      margin-bottom: 22px;
    }

    &__attraction {
      padding: 15px 10px 10px;
      border: 1px solid #c4c4c4;
      border-radius: 4px;
    }

    &__list {
      padding-top: 27px;
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
