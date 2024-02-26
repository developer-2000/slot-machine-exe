<template>
  <div class="statistics-container">
    <el-tabs type="card" @tab-click="handleClick">
      <el-tab-pane
        :label="$t('statistics.players')"
        :xs="24"
        v-loading="listLoading"
      >
        <ul v-if="statistic.gamer" class="licenses__list licenses-list">
          <li class="licenses-list__item">
            <p>{{ $t("statistics.registeredPlayers") }}:</p>
            <span class="value">{{
              statistic.gamer.number_registered_players || "0"
            }}</span>
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.playersPerDay") }}:</p>
            <span class="value">{{
              statistic.gamer.average_amount_day || "0"
            }}</span>
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.playersPerMonth") }}:</p>
            <span class="value">{{
              statistic.gamer.average_amount_month || "0"
            }}</span>
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.avgAge") }}:</p>
            <span class="value"> {{ statistic.gamer.average_age || "0" }}</span>
          </li>
          <li class="licenses-list__item licenses-list__item_last">
            <p>{{ $t("statistics.avgPlayersPerSession") }}:</p>
            <span class="value">{{
              statistic.gamer.average_amount_players_session || "0"
            }}</span>
          </li>
        </ul>
      </el-tab-pane>

      <el-tab-pane
        :label="$t('statistics.locations')"
        :xs="24"
        v-loading="listLoading"
      >
        <ul v-if="statistic.location" class="licenses__list licenses-list">
          <li class="licenses-list__item">
            <p>{{ $t("statistics.locationsQty") }}:</p>
            <span class="value">{{
              statistic.location.total_number_locations || "0"
            }}</span>
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.minAmusementsQty") }}:</p>
            <span
              class="value"
              v-if="statistic.location.minimum_attractions_location"
              >{{
                statistic.location.minimum_attractions_location.attraction_count
              }}
              (id:{{
                statistic.location.minimum_attractions_location.location_id
              }},
              {{ $t('profile.locationName')}}: {{
                statistic.location.minimum_attractions_location.location_title
              }})</span
            >
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.maxAmusementsQty") }}:</p>
            <span
              class="value"
              v-if="statistic.location.maximum_attractions_location"
              >{{
                statistic.location.maximum_attractions_location.attraction_count
              }}
              (id:{{
                statistic.location.maximum_attractions_location.location_id
              }},
              {{ $t('profile.locationName')}}: {{
                statistic.location.maximum_attractions_location.location_title
              }})</span
            >

          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.minPlayersQtyDay") }}:</p>
             <span
              class="value"
              v-if="statistic.location.minimum_players_location_day"
              >{{
                statistic.location.minimum_players_location_day.count
              }}
              (id:{{
                statistic.location.minimum_players_location_day.location_id
              }},
              {{ $t('profile.locationName')}}: {{
                statistic.location.minimum_players_location_day.location_title
              }})</span
            >
           
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.maxPlayersQtyDay") }}:</p>
             <span
              class="value"
              v-if="statistic.location.maximum_players_location_day"
              >{{
                statistic.location.maximum_players_location_day.count
              }}
              (id:{{
                statistic.location.maximum_players_location_day.location_id
              }},
              {{ $t('profile.locationName')}}: {{
                statistic.location.maximum_players_location_day.location_title
              }})</span
            >
         
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.minPlayersQtyMonth") }}:</p>
               <span
              class="value"
              v-if="statistic.location.minimum_players_location_month"
              >{{
                statistic.location.minimum_players_location_month.count
              }}
              (id:{{
                statistic.location.minimum_players_location_month.location_id
              }},
              {{ $t('profile.locationName')}}: {{
                statistic.location.minimum_players_location_month.location_title
              }})</span
            >
         
          </li>
          <li class="licenses-list__item licenses-list__item_last">
            <p>{{ $t("statistics.maxPlayersQtyMonth") }}:</p>
                   <span
              class="value"
              v-if="statistic.location.maximum_players_location_month"
              >{{
                statistic.location.maximum_players_location_month.count
              }}
              (id:{{
                statistic.location.maximum_players_location_month.location_id
              }},
              {{ $t('profile.locationName')}}: {{
                statistic.location.maximum_players_location_month.location_title
              }})</span
            >
           
          </li>
        </ul>
      </el-tab-pane>

      <el-tab-pane
        disabled="disabled"
        :label="$t('statistics.gameModes')"
        v-loading="listLoading"
      >
        <ul class="licenses__list licenses-list">
          <li class="licenses-list__item">
            <p>{{ $t("statistics.gamesPerMonth") }}:</p>
            <span class="value">123</span>
          </li>
          <li class="licenses-list__item">
            <p>{{ $t("statistics.playersPerMonth") }}:</p>
            <span class="value">99999</span>
          </li>
          <li class="licenses-list__item licenses-list__item_last">
            <p>{{ $t("statistics.avgSessionTime") }}:</p>
            <span class="value">9:00</span>
          </li>
        </ul>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { fetchStatisticGamer, fetchStatisticLocation } from "@/api/statistics";
export default {
  data() {
    return {
      activeName: "first",
      listLoading: true,
      statistic: {
        gamer: {},
        location: {},
      },
    };
  },
  async created() {
    await this.onGetStatistic("gamer");
  },
  methods: {
    async onGetStatistic(type) {
      try {
        this.listLoading = true;
        if (type === "gamer" && Object.keys(this.statistic.gamer).length == 0) {
          const { data: statisticGamer } = await fetchStatisticGamer();
          this.statistic.gamer = statisticGamer.message;
        } else if (
          type === "location" &&
          Object.keys(this.statistic.location).length == 0
        ) {
          const { data: statisticLocation } = await fetchStatisticLocation();
          this.statistic.location = statisticLocation.message;
        }

        this.listLoading = false;
      } catch (error) {
        this.listLoading = false;
        console.log(error);
      }
    },
    async handleClick(tab, event) {
      if (tab.index == 1) {
        await this.onGetStatistic("location");
      }
    },
  },
  name: "Players",
  computed: {
    ...mapGetters(["name"]),
  },
};
</script>

<style>
.el-tabs--card > .el-tabs__header .el-tabs__nav {
  border: none;
}
</style>

<style lang="scss" scoped>
.statistics {
  &-container {
    padding-top: 22px;
  }
}
</style>
<style lang="scss">
.el-tabs__item.is-disabled {
  cursor: not-allowed;
}
@import "~@/styles/variables";

@mixin font($size, $weight, $line-height, $color: null) {
  color: $color;
  font-size: $size;
  font-weight: $weight;
  line-height: $line-height;
}

@mixin btn-style {
  background-color: $brand_blue;
  border: 2px solid $brand_blue;

  &:hover {
    background-color: $brand_darken_blue;
    border: 2px solid $brand_darken_blue;
  }

  &.is-active {
    background-color: #fff;
    border: 2px solid currentColor;
    color: $brand_blue;
  }
}
/*Переопределение стилей*/
.el-tabs--card > .el-tabs__header {
  border: none;
}
.el-tabs--card > .el-tabs__header .el-tabs__nav {
  max-width: 691px;
  width: 100%;
  display: flex;
  justify-content: space-between;

  @media screen and (max-width: 576px) {
    flex-direction: column;
  }
}

.el-tabs--card > .el-tabs__header .el-tabs__item {
  max-width: 210px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 3px;
  text-align: center;
  text-transform: uppercase;
  transition: background-color 0.3s ease-in, border 0.3s ease-in,
    color 0.3s ease-in;

  @include font(12px, 700, 14px, #fff);

  @include btn-style;
}

.el-tabs__content {
  margin-top: 40px;
}
.licenses {
  &-list {
    list-style: none;
    font-size: 16px;
    line-height: 18px;

    &__item {
      width: 100%;
      min-height: 74px;
      padding: 10px 26px;
      display: flex;
      align-items: center;

      &:nth-of-type(odd) {
        background: rgba(175, 187, 203, 0.1);
      }

      p {
        max-width: 500px;
        width: 100%;
        font-weight: 700;
      }

      .value {
        display: flex;
        flex: 1 1 36%;
      }

      &_last {
        margin-bottom: 0;
      }
    }
  }
}
</style>
