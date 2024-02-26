<template>
  <div class="location-container">
    <!--Строка поиска по Названию/ID/ФИО-->
    <search-field @search="onSearch" class="search-field" />

    <!--Таблица локаций - колонки формируются в data через columns, данные из js-файлы locations-->
    <el-table
      :data="locations"
      @row-click="rowClicked"
      v-loading="listLoading"
      @sort-change="onSort"
      :default-sort="{ prop: 'name', order: 'descending' }"
      :row-class-name="tableRowClassName"
      style="width: 100%"
    >
      <!--Владелец (ФИО+Email) - Кликабельно-->
      <!-- <el-table-column
        :sortable="column.sortable ? true : false"
        v-for="column in columns"
        :key="column.prop"
        :formatter="formatter"
        v-bind="column"
      ></el-table-column> -->
      <el-table-column prop="id" :label="$t('table.id')" min-width="100">
        <template slot-scope="scope">
          {{ scope.row.location_id || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="title"
        :label="$t('table.locationName')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.title || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="address"
        :label="$t('table.address')"
        min-width="140"
      >
        <template slot-scope="scope">
          {{
            `${
              scope.row.address && scope.row.address.region
                ? scope.row.address.region.name
                : ""
            }${
              scope.row.address.country && scope.row.address.country.name
                ? ", " + scope.row.address.country.name
                : ""
            }${
              scope.row.address.city && scope.row.address.city.name
                ? ", " + scope.row.address.city.name
                : ""
            }${
              scope.row.address && scope.row.address.street
                ? scope.row.address.street !== null &&
                  typeof scope.row.address.street === "object"
                  ? ", " + scope.row.address.street.name
                  : ", " + scope.row.address.street
                : ""
            }` || $t("table.notFilled")
          }}</template
        >
      </el-table-column>

      <el-table-column
        prop="first_name"
        :label="$t('table.owner')"
        min-width="140"
      >
        <template slot-scope="scope">
          {{
            `${scope.row.first_name ? scope.row.first_name + " " : ""}${
              scope.row.middlename ? scope.row.middlename + " " : ""
            }${scope.row.surname ? scope.row.surname + " " : ""}${
              scope.row.email
            }` || $t("table.notFilled")
          }}</template
        >
      </el-table-column>

      <el-table-column
        prop="attractions"
        :label="$t('table.amusementsQty')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.count_attractions !==null ?scope.row.count_attractions: $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="activation"
        :label="$t('table.status')"
        sortable="custom"
      >
        <template slot-scope="scope">
          <el-tag
            class="status-cell"
            :type="scope.row.activation == true ? 'success' : 'primary'"
            disable-transitions
            >{{ scope.row.activation }}</el-tag
          >
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')">
        <template slot-scope="scope">
           <router-link
            :to="{
              name: 'EditLocation',
              params: {
                id: scope.row.location_id,
              },
            }"
          >
          <el-button
            class="edit-btn"
            size="mini"
          >
            Edit
          </el-button>
             </router-link>
        </template>
      </el-table-column>
    </el-table>

    <!--Компонент пагинации, если объекты только на 1 странице - пагинация не видна-->
    <pagination
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="onPagination"
    />
    <el-dialog
      width="max-content"
      style="min-width:250px"
      :title="$t('attraction.location')"
      :visible.sync="isOpenModal"
    >
      <div class="dialog-wrap">
        <div
          class="row-dialog"
          v-for="(value, name, index) in location"
          :key="index"
        >
          <div style="flex:0 0 40%;font-weight:500">{{ $t(name) }}:</div>
          <div>{{ value || $t("table.notFilled") }}</div>

          <!-- <el-input type="text" readonly="readonly" :value="value"></el-input> -->
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import SearchField from "@/components/SearchField";
import Pagination from "@/components/Pagination/index";
import { fetchList } from "@/api/locations";
import { getLocationAndAttr } from "@/api/locations";
export default {
  name: "Locations",
  components: {
    Pagination,
    SearchField,
  },
  data() {
    return {
      locations: [],
      listLoading: true,
      isOpenModal: false,
      location: "",
      sort: {
        name: "",
        order: "",
      },
      search: "",
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      columns: [
        {
          prop: "id",
          label: "ID",
          minWidth: "100",
        },
        {
          prop: "title",
          label: "Имя",
          minWidth: "120",
          sortable: true,
        },
        {
          prop: "address",
          label: "Адрес",
          minWidth: "140",
        },
        {
          prop: "owner",
          label: "Владелец",
          minWidth: "140",
        },
        {
          prop: "count_attractions",
          label: "Кол-во аттракционов",
          minWidth: "120",
          sortable: true,
        },
      ],
    };
  },
  computed: {
    ...mapGetters(["name"]),
  },
  async created() {
    await this.onGetLocations({});
  },
  methods: {
    async rowClicked(row) {
      const id = row.location_id;
      let { data } = await getLocationAndAttr(id);
      const loc = data.message;
      let hours = "";
      if (loc.working_hours && loc.working_hours.open) {
        const open = this.$d(
          loc.working_hours.open * 1000 +
            new Date(0).getTimezoneOffset() * 60 * 1000,
          "time"
        );
        const close = this.$d(
          loc.working_hours.close * 1000 +
            new Date(0).getTimezoneOffset() * 60 * 1000,
          "time"
        );
        hours = `${open} ${this.$t("form.to")} ${close}`;
      }

      const street =
        loc.street !== null && typeof loc.street === "object"
          ? loc.street
          : { name: loc.street ||'' };
          const address=`${loc.region.name}, ${loc.country.name}, ${loc.city.name}${street.name ?", "+street.name:
          ''} `
      this.location = {
        "table.title": loc.title,
        "table.address":address,
        "table.status": loc.activation ? true : false,
        "form.price": loc.price,
        "form.latitude": street.latitude,
        "form.longitude": street.longitude,
        "form.street": street.name,
        "clients.workingHours": hours,
      };
      this.isOpenModal = true;
    },
    onSearch(text) {
      this.search = text;
      this.onGetLocations({ filter: this.search, page: 1, sort: this.sort });
    },
    async onGetLocations({ page = 1, limit = 10, sort = {} }) {
      try {
        this.listLoading = true;
        const { data } = await fetchList(
          this.search,
          page,
          limit,
          sort.name,
          sort.order
        );
        this.locations = data?.message?.data;
        this.total = data?.message?.total;
        this.listQuery.page = data?.message?.current_page;
        this.listQuery.limit = data?.message?.per_page;
        this.listLoading = false;
      } catch (error) {
        console.log(error);
      }
    },
    async onPagination(pagination) {
      await this.onGetLocations({ ...pagination, sort: this.sort });
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
      this.onGetLocations({
        filter: this.search,
        page: 1,
        sort: this.sort,
      });
    },
    filterTag(value, row) {
      return row.tag == value;
    },
    filterStatus(value, row) {
      return row.license === value;
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

<style lang="scss">
$brand_blue: #268597;

.location {
  &-container {
    padding-top: 22px;

    .search-field {
      margin-bottom: 25px;
    }
  }
}
</style>
