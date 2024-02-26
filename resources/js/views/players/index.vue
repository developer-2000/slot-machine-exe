<template>
  <div class="players-container">
    <!--Строка поиска по ФИО/Нику-->
    <search-field @search="onSearch" class="search-field" />

    <!--Таблица игроков - колонки формируются в data через columns, данные из js-файла players-->
    <el-table
      v-loading="listLoading"
      :data="players"
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
        <template slot-scope="scope">
          {{ scope.row.id || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="first_name"
        :label="$t('table.firstname')"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.first_name || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="nickname"
        :label="$t('table.nickname')"
        min-width="140"
      >
        <template slot-scope="scope">
          {{ scope.row.nickname || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="date_of_birth"
        :label="$t('table.birthday')"
        sortable="custom"
        min-width="140"
      >
        <template slot-scope="scope">
          {{
            new Date(scope.row.date_of_birth).toLocaleDateString("ru-RU") ||
              $t("table.notFilled")
          }}</template
        >
      </el-table-column>

      <el-table-column
        prop="last_game"
        :label="$t('table.lastgame')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          <el-tooltip
            placement="top"
            v-if="
              scope.row.last_location && scope.row.last_location.location_id
            "
          >
            <div slot="content">
              <span
                v-if="
                  scope.row.last_location.location_id ||
                    scope.row.last_location.title
                "
              >
                <p v-if="scope.row.last_location.location_id">
                  id: {{ scope.row.last_location.location_id }}
                </p>
                <p v-if="scope.row.last_location.title">
                  title: {{ scope.row.last_location.title }}
                </p>
              </span>
            </div>
            <p style="cursor:pointer">
              {{ scope.row.last_game || $t("table.notFilled") }}
            </p>
          </el-tooltip>
          <p v-else style="cursor:pointer">
            {{
              scope.row.last_game !== null
                ? scope.row.last_game
                : $t("table.notFilled")
            }}
          </p>
        </template>
      </el-table-column>

      <el-table-column
        prop="achievements"
        :label="$t('table.achievements')"
        sortable="custom"
        min-width="120"
      >
        <template slot-scope="scope">
          {{
            scope.row.count_achievements !== null
              ? scope.row.count_achievements
              : $t("table.notFilled")
          }}</template
        >
      </el-table-column>

      <el-table-column
        prop="count_game"
        :label="$t('table.games')"
        sortable="custom"
        min-width="60"
      >
        <template slot-scope="scope">
          {{ scope.row.count_game || $t("table.notFilled") }}</template
        >
      </el-table-column>

      <el-table-column
        prop="favorite_mode"
        :label="$t('table.favouriteMode')"
        sortable="custom"
        min-width="140"
      >
        <template slot-scope="scope">
          {{ scope.row.favorite_mode || $t("table.notFilled") }}</template
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
import { fetchList } from "@/api/players";
import SearchField from "@/components/SearchField";
import Pagination from "@/components/Pagination/index";
//TODO Спросить про дату, устанавливается ли она с учетом часового пояса юзера
export default {
  name: "Players",
  components: {
    Pagination,
    SearchField,
  },
  data() {
    return {
      players: [],
      listLoading: true,
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
          prop: "first_name",
          label: "Имя",
          minWidth: "120",
        },
        {
          prop: "nickname",
          label: "Ник",
          minWidth: "140",
        },
        {
          prop: "date_of_birth",
          label: "Дата рождения",
          minWidth: "140",
          sortable: true,
        },
        {
          prop: "last_game",
          label: "Последняя игра",
          minWidth: "120",
          sortable: true,
        },
        {
          prop: "progress",
          label: "Достижений",
          minWidth: "120",
          sortable: true,
        },
        {
          prop: "count_game",
          label: "Игр",
          minWidth: "60",
          sortable: true,
        },
        {
          prop: "favorite_mode",
          label: "Любимый режим",
          minWidth: "140",
          sortable: true,
        },
      ],
    };
  },
  computed: {
    ...mapGetters(["name"]),
  },
  async created() {
    await this.onGetPlayers({});
  },
  methods: {
    async onPagination(pagination) {
      await this.onGetPlayers({ ...pagination, sort: this.sort });
    },
    formatter(row, column) {
      if (column.property === "date_of_birth") {
        //TODO подставлять формат учитывая язык
        return new Date(row.date_of_birth).toLocaleDateString("ru-RU");
      }
      return row[column.property] === null
        ? "не указано"
        : row[column.property];
    },
    filterTag(value, row) {
      return row.activation == value;
    },
    async onGetPlayers({ page = 1, limit = 10, sort = {} }) {
      try {
        this.listLoading = true;
        const { data } = await fetchList(
          this.search,
          page,
          limit,
          sort.name,
          sort.order
        );
        this.players = data?.message?.data;
        this.total = data?.message?.total;
        this.listQuery.page = data?.message?.current_page;
        this.listQuery.limit = data?.message?.per_page;
        this.listLoading = false;
      } catch (error) {
        console.log(error);
      }
    },
    onSearch(text) {
      this.search = text;
      this.onGetPlayers({ filter: this.search, page: 1, sort: this.sort });
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
      this.onGetPlayers({
        filter: this.search,
        page: 1,
        sort: this.sort,
      });
    },
    handleEdit(index, row) {
      console.log(index, row);
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

<style lang="scss" scoped>
$brand_blue: #268597;

.players {
  &-container {
    padding-top: 22px;

    ::v-deep .search-field {
      margin-bottom: 25px;
    }

    .edit-btn {
      background-image: url("/images/actions_info.svg") !important;
    }
  }
}
</style>
