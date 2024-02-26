<template>
  <div class="new-client__container">
    <el-button
      type="primary"
      v-if="step > 0"
      class="profile__pay"
      @click="step = 0"
      >{{ $t("buttons.back") }}</el-button
    >
    <el-steps
      :active="step"
      finish-status="success"
      simple
      style="margin-top: 20px"
    >
      <el-step :title="$t('clients.heading')">
        <template slot-scope="scope">
          {{ scope.row.license || $t("table.notFilled") }}</template
        >
      </el-step>
      <el-step :title="$t('clients.headingLocation')"></el-step>
    </el-steps>
    <!-- <el-col :xs="24" :sm="10" :md="10" :lg="7" :xl="6"> -->
    <el-form
      v-if="step === 0"
      :label-position="labelPosition"
      label-width="140px"
      ref="ruleForm"
      :rules="rules"
      class="form-step"
      :model="formClient"
    >
      <div class="row-custom">
        <el-form-item prop="first_name" :label="$t('table.firstname')">
          <el-input v-model="formClient.first_name" />
        </el-form-item>

        <el-form-item prop="surname" :label="$t('table.surname')">
          <el-input v-model="formClient.surname" />
        </el-form-item>
      </div>
      <div class="row-custom">
        <el-form-item :label="$t('table.middlename')" prop="middlename">
          <el-input v-model="formClient.middlename" />
        </el-form-item>

        <el-form-item prop="company" :label="$t('table.company')">
          <el-input v-model="formClient.company" />
        </el-form-item>
      </div>
      <div class="row-custom">
        <el-form-item :label="$t('table.email')" prop="email">
          <el-input v-model="formClient.email" />
        </el-form-item>

        <el-form-item :label="$t('table.tel')" prop="tell">
          <el-input v-model="formClient.tell" />
        </el-form-item>
      </div>
      <el-button
        class="new-client__submit"
        type="primary"
        @click="submitForm('ruleForm')"
        >{{ $t("buttons.continue") }}</el-button
      >
    </el-form>
    <!-- </el-col> -->

    <div v-if="step === 1" class="new-client__location">
      <el-row :gutter="40">
        <el-col :xs="24" :sm="24" :md="24" :lg="11" :xl="11">
          <el-form
            :rules="rulesLocation"
            :label-position="labelPosition"
            label-width="100px"
            ref="ruleFormLoc"
            :model="formLabelAlign"
          >
            <el-form-item prop="title" :label="$t('table.title')">
              <el-input v-model="formLabelAlign.title" />
            </el-form-item>

            <!-- <el-form-item :label="$t('table.address')">
              <el-input v-model="formLabelAlign.address" />
            </el-form-item> -->
            <el-form-item :label="$t('form.country')" prop="country">
              <el-select
                style="width:100%"
                v-model="formLabelAlign.country"
                filterable
                automatic-dropdown
                placeholder=""
                @focus="remoteMethodCountry"
                clearable
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
              :label="$t('form.country')"
              v-if="formLabelAlign.country"
            >
              <el-select
                v-model="formLabelAlign.region"
                style="width:100%"
                filterable
                clearable
                reserve-keyword
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
              :label="$t('form.city')"
              v-if="formLabelAlign.region"
            >
              <el-select
                v-model="formLabelAlign.city"
                style="width:100%"
                filterable
                clearable
                reserve-keyword
                @focus="remoteMethodCities"
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
                    @change="changeSelectOpen"
                    v-model="formLabelAlign.open"
                    clearable
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
                  >
                    <el-option
                      v-for="(item, index) in generateTime(formLabelAlign.open)"
                      :key="index"
                      :label="item.label"
                      :value="item.value"
                    >
                    </el-option>
                  </el-select>
                </el-form-item>
              </div>
            </el-form-item>

            <el-button
              class="new-client__submit"
              type="primary"
              @click="submitForm('ruleFormLoc')"
              >{{ $t("buttons.addLocation") }}</el-button
            >
            <el-button
              class="new-client__submit"
              type="primary"
              v-if="locations.length"
              @click="onSubmitClientAndLoc"
              >{{ $t("buttons.save") }}</el-button
            >
          </el-form>
        </el-col>
        <el-col :xs="24" :sm="24" :md="24" :lg="13" :xl="12">
          <h3 v-if="locations.length === 0" style="text-align:center">
            {{ $t("form.nolistloc") }}
          </h3>
          <form-item-add
            v-for="(item, index) in locations"
            :item="item"
            :key="index"
            @deleteItem="deleteItem(item.index)"
          />
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import TimePicker from "@/components/TimePicker/index";
import FormItemAdd from "@/components/FormItemAdd/index";
import {
  fetchCountry,
  fetchRegions,
  fetchCities,
  createClient,
} from "@/api/clients";
import { createLocation } from "@/api/locations";
export default {
  name: "NewClient",
  components: {
    TimePicker,
    FormItemAdd,
  },
  data() {
    return {
      step: 0,
      optionsCountry: [],
      optionsRegions: [],
      startTime: "",
      endTime: "",
      optionsCities: [],
      loading: false,
      labelPosition: "left",
      rules: {},
      rulesLocation: {},
      formClient: {
        first_name: "",
        surname: "",
        middlename: "",
        company: "",
        email: "",
        tell: "",
      },

      formLabelAlign: {
        title: "",
        country: "",
        region: "",
        city: "",
        price: "",
        latitude: "",
        longitude: "",
        street: "",
        close: "",
        open: "",
      },
      locations: [],
    };
  },
  created() {
    this.setRules();
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  watch: {
    language() {
      this.setRules();
    },
    "formLabelAlign.country": {
      handler(n, o) {
        this.remoteMethodRegions(true);
        this.formLabelAlign.region = "";
      },
    },
    "formLabelAlign.region": {
      handler(n, o) {
        this.remoteMethodCities(true);
        this.formLabelAlign.city = "";
      },
    },
  },
  methods: {
    setRules() {
      this.rules = {
        first_name: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.firstname") }),
            trigger: "blur",
          },
          {
            min: 3,
            max: 35,
            message: this.$t("form.len", { min: 3, max: 35 }),
            trigger: "blur",
          },
        ],
        surname: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.surname") }),
            trigger: "blur",
          },
          {
            min: 3,
            max: 35,
            message: this.$t("form.len", { min: 3, max: 35 }),
            trigger: "blur",
          },
        ],
        tell: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.tel") }),
            trigger: "blur",
          },
        ],

        middlename: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.middlename") }),
            trigger: "blur",
          },
          {
            min: 3,
            max: 35,
            message: this.$t("form.len", { min: 3, max: 35 }),
            trigger: "blur",
          },
        ],
        company: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.company") }),
            trigger: "blur",
          },
        ],

        email: [
          {
            required: true,
            message: this.$t("form.req", { f: this.$t("table.email") }),
            trigger: "blur",
          },
          {
            type: "email",
            message: this.$t("form.ema"),
            trigger: ["blur", "change"],
          },
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
    async remoteMethodCountry() {
      try {
        if (this.optionsCountry.length === 0) {
          this.loading = true;
          const { data } = await fetchCountry();
          this.optionsCountry = data.countries;
          this.loading = false;
        }
      } catch (error) {
        this.$message({
          message: "Error getting countries",
          type: "error",
        });
        this.loading = false;
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
        if (this.formLabelAlign.region !== "") {
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
    async onSubmitClientAndLoc() {
      try {
        let { data } = await createClient(this.formClient);
        let idClient = data.message?.message?.id;
        if (idClient) {
          for (const location of this.locations) {
            await createLocation({
              admin_id: idClient,
              title: location.title,
              working_hours: location.working_hours,
              country: JSON.stringify(location.address.country),
              region: JSON.stringify(location.address.region),
              city: JSON.stringify(location.address.city),
              price: location.price,
              street: JSON.stringify({
                name: location.street,
                latitude: location.latitude,
                longitude: location.longitude,
              }),
            });
          }
          this.$message({
            message: this.$t("result.client and locations added successfully"),
            type: "success",
          });
          this.$router.push(`/clients`);
        } else {
          throw "unknown user id";
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
            message: "Client creation error ",
            type: "error",
          });
        }
      }
      //
    },
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if (formName === "ruleForm") {
            this.step = 1;
          } else {
            let location = { ...this.formLabelAlign };
            location.working_hours = JSON.stringify({
              open: location.open,
              close: location.close,
            });
            location.address = {
              country: this.optionsCountry.find(
                (i) => i.code === location.country
              ),
              city: this.optionsCities.find((i) => i.code === location.city),
              region: this.optionsRegions.find(
                (i) => i.code === location.region
              ),
              street: location.street,
            };
            this.locations.push(location);
            Object.keys(this.formLabelAlign).map(
              (i) => (this.formLabelAlign[i] = "")
            );
          }
        } else {
          return false;
        }
      });
    },
    deleteItem(index) {
      this.locations.splice(index, 1);
    },
  },
};
</script>
<style lang="scss" scoped>
.el-range-editor.el-input__inner {
  max-width: 314px;
}
.form-step {
  .el-form-item {
    width: 48%;
  }
  margin: 10px 0;
}
.row-custom {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
</style>
<style lang="scss">
$brand_blue: #268597;

@mixin font($size, $weight, $line-height, $color: null) {
  color: $color;
  font-size: $size;
  font-weight: $weight;
  line-height: $line-height;
}
// .el-form-item__content{
//   display: block;
//   width: auto;
// }
.new-client {
  &__container {
    padding-top: 40px;
  }

  &__title {
    margin-bottom: 40px;
  }

  &__col {
    padding-top: 28px;
  }

  &__location {
    padding: 15px 10px 10px;
  }

  &__submit {
    margin-top: 20px;
    width: auto;
    text-transform: uppercase;
    font-weight: 700;
  }
}

.el-form--label-top .el-form-item__label {
  font-size: 16px;
  line-height: 18px;
}
</style>
