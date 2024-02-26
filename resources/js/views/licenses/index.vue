<template>
  <div class="licenses-container">
    <!--Поиск по ФИО, Email, телефону-->
    <search-field @search="onSearch" class="search-field" />
    <!--Фильтры | START -->
    <el-row>
      <div class="licenses-filters">
        <!-- licenses-filters__container -->
        <div class="licenses-filters__col">
          <span class="licenses-filters__heading">{{
            $t("filters.paymentAmout")
          }}</span>
          <div style="display:flex;padding-right:10px">
            <div class="licenses-filter licenses-sum el-button">
              <label class="licenses-sum__label">
                {{ $t("filters.from") }}
                <input
                  type="number"
                  name="minSum"
                  v-model="minSum"
                  class="licenses-sum__input"
                  min="0"
                />
                <!-- @keyup.enter="this.value" -->
              </label>

              <label class="licenses-sum__label licenses-sum__label_last">
                {{ $t("filters.to") }}
                <input
                  type="number"
                  name="maxSum"
                  style="width:60px;padding-right:13px"
                  v-model="maxSum"
                  :class="{
                    maxSumhover: maxSum && minSum,
                  }"
                  class="licenses-sum__input"
                  :min="minSum"
                />
                <span
                  class="el-input__suffix maxSumSuffix"
                  @click="onClearMinMax"
                  ><span class="el-input__suffix-inner"
                    ><i
                      class="el-input__icon el-icon-circle-close el-input__clear"
                    ></i></span
                ></span>
              </label>
            </div>
            <el-button
              class="licenses-filter licenses-filter--btn"
              type="primary"
              :disabled="!(maxSum > 0 && minSum > 0)"
              @click="onFilterSum"
            >
              {{ $t("filters.apply") }}</el-button
            >
          </div>
        </div>

        <div class="licenses-filters__col">
          <span class="licenses-filters__heading">{{
            $t("filters.paymentDate")
          }}</span>

          <date-picker
            @change="(e) => onGetPaypent('day', e)"
            clearable
            v-model="date"
            class="licenses-filter licenses-date"
          />
        </div>
        <div class="licenses-filters__col" style="display:flex">
          <div
            class="licenses-filters__col"
            style="margin-top:auto;margin-right:10px"
          >
            <el-button
              class="licenses-filter licenses-filter--btn"
              type="primary"
              @click="onGetPaypent('week')"
              >{{ $t("filters.paymentWeek") }}</el-button
            >
          </div>

          <div class="licenses-filters__col" style="margin-top:auto">
            <el-button
              class="licenses-filter licenses-filter--btn"
              type="primary"
              @click="onGetPaypent('mounth')"
              >{{ $t("filters.paymentMonth") }}</el-button
            >
          </div>
        </div>
      </div>
    </el-row>
    <!--Фильтры | END -->

    <!--Таблица лицензий - колонки формируются в data через columns, данные из js-файлы licenses-->
    <el-table
      v-loading="listLoading"
      :data="licenses"
      @sort-change="onSort"
      :default-sort="{ prop: 'name', order: 'descending' }"
      :row-class-name="tableRowClassName"
      style="width: 100%"
    >
      <!-- <el-table-column
        :sortable="column.sortable ? true : false"
        v-for="column in columns"
        :formatter="formatter"
        :key="column.prop"
        v-bind="column"
      ></el-table-column> -->

      <el-table-column prop="id" :label="$t('table.id')" min-width="100">
      </el-table-column>

      <el-table-column
        prop="firstname"
        :label="$t('table.firstname')"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.firstname || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="surname"
        :label="$t('table.surname')"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.surname || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="middlename"
        :label="$t('table.middlename')"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.middlename || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="location_id"
        :label="$t('table.locationID')"
        min-width="90"
      >
        <template slot-scope="scope">
          {{ scope.row.location_id || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column prop="email" :label="$t('table.email')" min-width="140">
        <template slot-scope="scope">
          {{ scope.row.email || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="data_last_payment"
        :label="$t('table.lastPaymentDate')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.data_last_payment || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="total_payment"
        :label="$t('table.totalPayment')"
        min-width="100"
      >
        <template slot-scope="scope">
          {{ scope.row.total_payment || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="count_attractions"
        :label="$t('table.amusements')"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.count_attractions !== null ?scope.row.count_attractions : $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column prop="license" :label="$t('table.license')">
        <template slot-scope="scope">
          <el-tag
            class="license-cell"
            :type="scope.row.payment == true ? 'success' : 'primary'"
            disable-transitions
            >{{
              scope.row.payment ? $t("table.paid") : $t("table.notPaid")
            }}</el-tag
          >
        </template>
      </el-table-column>

      <!-- </el-table-column> -->
    </el-table>

    <!--Компонент пагинации, если объекты только на 1 странице - пагинация не видна-->
    <pagination
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="onPagination"
    />
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import SearchField from "@/components/SearchField";
import DatePicker from "@/components/DatePicker/index";
import Pagination from "@/components/Pagination/index";
import { fetchList } from "@/api/licenses";
export default {
  name: "Licenses",
  components: {
    Pagination,
    SearchField,
    DatePicker,
  },
  computed: {
    ...mapGetters(["name"]),
  },
  data() {
    return {
      licenses: [],
      listLoading: true,
      total: 0,
      date: null,
      minSum: null,
      maxSum: null,
      sort: {
        name: "",
        order: "",
      },
      listQuery: {
        page: 1,
        limit: 10,
      },
      search: "",
      filter: "",
    };
  },
  async created() {
    await this.onGetLicenses({});
  },
  methods: {
    async onClearMinMax() {
      const isJson = (str) => {
        try {
          JSON.parse(str);
        } catch (e) {
          return undefined;
        }
        return JSON.parse(str);
      };
      this.minSum = "";
      this.maxSum = "";
      this.add_filter = JSON.stringify({
        ...isJson(this.add_filter),
        number: undefined,
      });
      await this.onGetLicenses({
        add_filter: this.add_filter,
        filter: this.search,
        page: 1,
        sort: this.sort,
      });
    },
    onSort({ prop, order }) {
      this.sort.name = prop;
      if (order === "descending") {
        this.sort.order = "desc";
      } else if (order === "ascending") {
        this.sort.order = "asc";
      } else {
        this.sort.order = order;
        this.sort.name = null;
      }
      this.onGetLicenses({
        filter: this.search,
        page: 1,
         add_filter: this.add_filter,
        sort: this.sort,
      });
    },
    async onGetPaypent(type, date) {
      const isJson = (str) => {
        try {
          JSON.parse(str);
        } catch (e) {
          return undefined;
        }
        return JSON.parse(str);
      };
      let d = new Date();
      if (type === "day") {
        if (date === null) {
          this.add_filter = JSON.stringify({
            number: isJson(this.add_filter)?.number,
          });
          await this.onGetLicenses({
            add_filter: this.add_filter,
            filter: this.search,
            page: 1,
            sort: this.sort,
          });
          return;
        }
        d = date;
      }
      if (type === "week") {
         this.date = null;
        d.setDate(d.getDate() - 7);
      }
      if (type === "mounth") {
         this.date = null;
        d.setMonth(d.getMonth() - 1);
      }
      let value = d
        .toLocaleDateString("ru-RU")
        .split(".")
        .reverse()
        .join("-");
      if (type === "week" || type === "mounth") {
        // this.search = value;
       
        this.add_filter = JSON.stringify({
          number: isJson(this.add_filter)?.number,
          period: value,
        });
      } else {
        this.add_filter = JSON.stringify({
          number: isJson(this.add_filter)?.number,
          date: value,
        });
      }

      await this.onGetLicenses({
        add_filter: this.add_filter,
        filter: this.search,
        page: 1,
        sort: this.sort,
      });
    },
    async onFilterSum() {
      const isJson = (str) => {
        try {
          JSON.parse(str);
        } catch (e) {
          return {};
        }
        return JSON.parse(str);
      };
      try {
        this.add_filter = JSON.stringify({
          ...isJson(this.add_filter),
          number: { min: +this.minSum, max: +this.maxSum },
        });
        // this.search = JSON.stringify({ min: +this.minSum, max: +this.maxSum });

        await this.onGetLicenses({
          add_filter: this.add_filter,
          page: 1,
          sort: this.sort,
        });
      } catch (error) {
        console.log(error);
      }
    },
    async onGetLicenses({ add_filter = "", filter = "", page = 1, sort = {} }) {
      try {
        this.listLoading = true;
        const { data } = await fetchList(
          add_filter,
          filter,
          page,
          sort.name,
          sort.order
        );
        this.licenses = data?.message?.data;
        this.total = data?.message?.total;
        this.listQuery.page = data?.message?.current_page;
        this.listQuery.limit = data?.message?.per_page;
        this.listLoading = false;
      } catch (error) {
        console.log(error);
      }
    },
    async onPagination(pagination) {
      await this.onGetLicenses({ ...pagination, sort: this.sort, add_filter: this.add_filter, });
    },
    onSearch(text) {
      this.search = text;
      this.onGetLicenses({ filter: this.search, page: 1, sort: this.sort, add_filter: this.add_filter });
    },
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 0) {
        return "grey-row";
      }
      return "";
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep .el-date-editor.el-input {
  max-width: 170px !important;
}

$brand_blue: #268597;

.maxSumhover:hover + .maxSumSuffix {
  display: block;
}
.maxSumSuffix {
  cursor: pointer;
  display: none;
}
.maxSumSuffix,
.el-input__suffix {
  top: -12px;
  right: -4px;
}
.licenses {
  &-container {
    padding-top: 22px;

    ::v-deep .search-field {
      margin-bottom: 25px;
    }
  }

  &-filters {
    display: flex;
    margin-bottom: 5px;
    &__container {
      display: flex;
      align-items: flex-end;
      flex-wrap: wrap;
    }

    &__heading {
      display: block;
      margin-bottom: 3px;
    }

    &__col {
      width: 100%;
    }
  }

  // &-filter {
  //   width: 170px;
  // }

  &-sum {
    padding: 11px 7px;

    &:hover {
      background: #fff;
      color: inherit;
    }

    &__label {
      font-weight: 400;

      &_last {
        position: relative;
        padding-left: 3px;

        &:before {
          position: absolute;
          top: 4px;
          left: 0;
          content: "";
          width: 1px;
          height: 10px;
          background: #606266;
        }
      }
    }

    &__input {
      width: 50px;
      border: none;
      outline: none;
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
}
</style>
