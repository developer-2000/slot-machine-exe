<template>
  <div class="clients-container">
    <add-btn :to="{ name: 'AddNewClient' }" />

    <!-- Строка поиска по ФИО/названию компании/ID-->
    <search-field @search="onSearch" class="search-field" />
    <el-table
      v-loading="listLoading"
      @sort-change="onSort"
      @row-click="rowClicked"
      :data="clients"
      :default-sort="{ prop: 'name', order: 'descending' }"
      :row-class-name="tableRowClassName"
    >


      <el-table-column prop="id" :label="$t('table.id')" min-width="100">
      </el-table-column>

      <el-table-column
        prop="first_name"
        :label="$t('table.firstname')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.first_name || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="surname"
        :label="$t('table.surname')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.surname || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="middlename"
        :label="$t('table.middlename')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.middlename || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="company"
        :label="$t('table.company')"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.company || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column prop="email" :label="$t('table.email')" min-width="100">
        <template slot-scope="scope">
          {{ scope.row.email || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column prop="tell" :label="$t('table.tel')" min-width="120">
        <template slot-scope="scope">
          {{ scope.row.tell || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column prop="license" :label="$t('table.license')">
        <template slot-scope="scope">
          {{ scope.row.license || $t("table.notFilled") }}</template
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

      <el-table-column :label="$t('table.actions')" min-width="120">
        <template slot-scope="scope">
          <router-link
            :to="{
              name: 'ClientCard',
              params: {
                id: scope.row.id,
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
    <pagination
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="onPagination"
    />
    <el-dialog
      width="max-content"
      style="min-width:250px"
      :title="$t('clients.client')"
      :visible.sync="isOpenModal"
    >
      <div class="dialog-wrap">
        <div
          class="row-dialog"
          v-for="(value, name, index) in client"
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
import TableInner from "@/components/TableInner";
import AddBtn from "@/components/AddBtn/index";
import Pagination from "@/components/Pagination/index";
import { fetchList } from "@/api/clients";
import { getProfile } from "@/api/user";
export default {
  name: "Clients",
  components: {
    Pagination,
    SearchField,
    TableInner,
    AddBtn,
  },
  //TODO не работает сортировка по компании по email tel в клиентах. http://ch-throw.ru/admin/clients/get_clients?page=1&sort=company&operator=asc
  //TODO http://ch-throw.ru/admin/location/all_locations_pagination?page=1&filter=Локация юзера не пускает "Undefined index: operator". нужно сорт поле сделать не обязательным
  //TODO http://ch-throw.ru/admin/gamers/all_gamers?page=1&sort=date_of_birth&operator=asc сортировка по дате рождения не пашет и по nickname first_name count_game activation
  //TODO в игроки добавить поле id последней локации
  //TODO сортировка не пашет в лицензиях и поля для даты надо обсудить
  data() {
    return {
      clients: [],
      isOpenModal: false,
      listLoading: true,
      search: "",
      client: "",
      sort: {
        name: "",
        order: "",
      },
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
    };
  },
  async created() {
    await this.onGetClients({});
  },
  methods: {
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
      this.onGetClients({
        filter: this.search,
        page: 1,
        sort: this.sort,
      });
    },
    async onPagination(pagination) {
      await this.onGetClients({ ...pagination, sort: this.sort });
    },
    onSearch(text) {
      this.search = text;
      this.onGetClients({ filter: this.search, page: 1, sort: this.sort });
    },
    async rowClicked(row) {
      const id = row.id;
      let { data } = await getProfile(id);
      this.client = {
        "table.title": data.first_name,
        "table.status": data.activation ? true : false,
        "table.surname": data.surname,
        "table.middlename": data.middlename,
        "table.company": data.company,
        "table.email": data.email,
        "table.tel": data.tell,
      };
      this.isOpenModal = true;
    },
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 0) {
        return "grey-row";
      }
      return "";
    },
    async onGetClients({ page = 1, limit = 10, sort = {} }) {
      try {
        this.listLoading = true;
        const { data } = await fetchList(
          this.search,
          page,
          limit,
          sort.name,
          sort.order
        );
        this.clients = data?.message?.data;
        this.total = data?.message?.total;
        this.listQuery.page = data?.message?.current_page;
        this.listQuery.limit = data?.message?.per_page;
        this.listLoading = false;
      } catch (error) {
        console.log(error);
      }
    },
  },
  computed: {
    ...mapGetters(["name"]),
  },
};
</script>

<style lang="scss" scoped>
$brand_blue: #268597;


.el-link.is-underline:hover:after {
  content: none;
}
.clients {
  &-container {
    padding-top: 22px;

    ::v-deep .search-field {
      margin-bottom: 25px;
    }
  }

  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
</style>
