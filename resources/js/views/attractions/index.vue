<template>
  <div class="attractions-container">
    <!--Строка поиска по UID-->
    <search-field @search="onSearch" class="search-field" />

    <!--Таблица локаций - колонки формируются в data через columns, данные из js-файлы attractions-->
    <el-table
      v-loading="listLoading"
      :data="attractions"
       @row-click="rowClicked"
      @sort-change="onSort"
      :default-sort="{ prop: 'name', order: 'descending' }"
      :row-class-name="tableRowClassName"
      style="width: 100%"
    >
      <el-table-column prop="id" :label="$t('table.id')" min-width="100">
        <template slot-scope="scope">
          {{ scope.row.id || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="location_id"
        :label="$t('table.locationID')"
        sortable="custom"
        min-width="100"
      >
        <template slot-scope="scope">
          {{ scope.row.location_id || $t("table.notFilled") }}</template
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
              name: 'EditAttraction',
              params: {
                id: scope.row.id,
                locId: scope.row.locId,
                owner: scope.row.owner,
              },
            }"
          >
            <el-button
              class="edit-btn"
              size="mini"
              >Edit</el-button
            >
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <!--Компонент пагинации, если объекты только на 1 странице - пагинация не видна-->
    <!-- <pagination :total="total" :page="page" :limit="limit" /> -->
    <pagination
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="onPagination"
    />
        <el-dialog
      width="max-content"
      style="min-width:250px"
      :title="$t('profile.amusement')"
      :visible.sync="isOpenModal"
    >
      <div class="dialog-wrap">
        <div
          class="row-dialog"
          v-for="(value, name, index) in attraction"
          :key="index"
        >
       
          <div style="flex:0 0 40%;font-weight:500">{{ $t(name) }}:</div>
          <div v-if="name ==='table.status'">
               <el-tag
            class="status-cell"
            :type="value ? 'success' : 'primary'"
            disable-transitions
            >{{ value }}</el-tag
          >
          </div>
          <div v-else>{{ value || $t("table.notFilled") }}</div>

          <!-- <el-input type="text" readonly="readonly" :value="value"></el-input> -->
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SearchField from "@/components/SearchField";
import Pagination from "@/components/Pagination/index";
import { getProfile } from "@/api/user";
import { fetchList } from "@/api/attractions";
import {
  getAttraction,
} from "@/api/attractions";
export default {
  name: "Attractions",
  components: {
    Pagination,
    SearchField,
  },
  data() {
    return {
      attractions: [],
      attraction:"",
      listLoading: true,
      isOpenModal: false,
      total: 0,
      sort: {
        name: "",
        order: "",
      },
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
          prop: "location_id",
          label: "ID Локации",
          minWidth: "100",
          sortable: true,
        },
      ],
      search: "",
    };
  },
  computed: {
    userId() {
      return this.$store.getters.user_id;
    },
  },
  async created() {
    await this.onGetAttractions({});
  },
  methods: {
    async rowClicked(row) {
      const id = row.id;
      let { data } = await getAttraction(id,this.userId);
      const attr = data[0].attraction;
       const user = await getProfile(attr.user_id);
      this.attraction = {
        "table.title": attr.title,
        "table.status": attr.activation ? true : false,
        "table.owner": `${user.first_name ? user.first_name + " " : ""}${
                user.middlename ? user.middlename + " " : ""
              }${user.surname ? user.surname + " " : ""}${user.email}`,
        "table.locationID": attr.location_id,
      };
      this.isOpenModal = true;
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
      this.onGetAttractions({
        filter: this.search,
        page: 1,
        sort: this.sort,
      });
    },
    async onPagination(pagination) {
      await this.onGetAttractions({ ...pagination, sort: this.sort });
    },
    async onGetAttractions({ page = 1, limit = 10, sort = {} }) {
      try {
        this.listLoading = true;
        const { data } = await fetchList(
          this.search,
          page,
          limit,
          sort.name,
          sort.order
        );
        this.attractions = data.message.data;
        this.total = data.message.total;
        this.listQuery.page = data.message.current_page;
        this.listQuery.limit = data.message.per_page;
        this.listLoading = false;
      } catch (error) {
        console.log(error);
      }
    },
    onSearch(text) {
      this.search = text;
      this.onGetAttractions({ filter: this.search, page: 1, sort: this.sort });
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

.attractions {
  &-container {
    padding-top: 22px;

    ::v-deep .search-field {
      margin-bottom: 25px;
    }
  }

  &-add {
    width: 160px;
    height: 30px;
    margin: 20px 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: $brand_blue;
    border-radius: 3px;
    color: #fff !important;
    transition: background 0.3s ease-in, color 0.3s ease-in;

    &:hover {
      background: darken($brand_blue, 10%);
      color: #fff;
    }
  }
}
</style>
