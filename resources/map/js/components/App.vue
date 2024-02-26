<template>
  <div id="app">
    <Map v-if="!loading" :locations="locations" :param="param" />
  </div>
</template>

<script>
import Map from "../components/Map";

export default {
  el: "#app",
  components: {
    Map,
  },

  data() {
    return {
      loading: true,
      origin: window.location.origin,
      param: {},
      langs: ["ru", "en", "es"],
      locations: [],
    };
  },
  metaInfo() {
    const { appName } = window.config;
    return {
      title: appName,
      titleTemplate: `%s · ${appName}`,
    };
  },
  async mounted() {
    //Для тестов
    // const { origin } = window.location;
    this.langs;
    const origin = this.origin;
    const uri = window.location.search.substring(1);
    const params = new URLSearchParams(uri);
    Array.from(params).forEach(([key, value]) => {
      this.param[key] = value;
    });
    if (this.param.lang && this.langs.includes(this.param.lang)) {
      this.$i18n.locale = this.param.lang;
    } else {
      throw "Failed to get lang or use only 'ru','en','es'";
    }

    let url = origin + "/api/visited_locations/get_locations_user";
    try {
      if (!this.param.token) {
        throw "Failed to get token";
      }
      //первый раз стучимся проверяем играет ли игрок
      let isPlayNow = await this.onGetSession();
      console.log("isPlayNow", isPlayNow);
      if (!isPlayNow) {
        if (!(this.param.latitude || this.param.longitude)) {
          if (!this.param.userid) {
            throw "Failed to get user id";
          }

          let isgetUser = await this.onGetUser();
          console.log("isgetUser", isgetUser);
          if (!isgetUser) {
            //center USA
            this.param.latitude = 39.8077291;
            this.param.longitude = -101.9739584;
            this.param.zoom = 4;
          }
        }
      }

      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${this.param.token}`,
        },
      });
      const { all_locations, locations_user } = await response.json();
      console.log(
        "all_locations",
        all_locations,
        "locations_user",
        locations_user
      );
      if (!all_locations) {
        throw "Failed to get locations ";
      }
      const locationsUserId = locations_user.map((i) => i.id);
      this.locations = all_locations
        // .filter(i => i.activation == true)
        .map(
          ({
            user_id,
            activation,
            price,
            created_at,
            updated_at,
            ...location
          }) => {
            location.visit = false;
            // console.log(
            //   typeof location.street === "object",
            //   location.street !== null,
            //   location.street
            // );
            if (
              location.street !== null &&
              typeof location.street === "object"
            ) {
              location.lat = +location.street.latitude;
              location.lng = +location.street.longitude;
              location.street = location.street.name;
              console.log("location have street", location);
            } else {
              location.lat = location.city.latitude + Math.random() / 200;
              location.lng = location.city.longitude + Math.random() / 200;
            }

            if (locationsUserId.includes(location.id)) {
              location.visit = true;
            }
            return location;
          }
        );
      // console.log(this.locations);
      // console.log("this.param", this.param);
      this.loading = false;
      this.$loading = this.$refs.loading;
    } catch (error) {
      alert(error);
      console.error("Ошибка:", error);
    }
  },
  methods: {
    async onGetSession() {
      try {
        console.log("onGetSession");
        const sessionResponse = await fetch(
          this.origin + "/api/session/check_gamer_session",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${this.param.token}`,
            },
          }
        );
        let session = await sessionResponse.json();
        console.log(session);
        if (session && session.connected_to_session) {
          let street = session.session?.sessions?.attraction?.location?.street;
          if (street && street.latitude && street.longitude) {
            this.param.latitude = street.latitude + "";
            this.param.longitude = street.longitude + "";
            return true;
          } else {
            console.log("street без координат");
            return false;
          }
        } else {
          console.log("session.connected_to_session is false or null");
          return false;
        }
      } catch (error) {
        console.log("catch error", error);
        return false;
      }
    },
    async onGetUser() {
      console.log("onGetUser");
      try {
        const responseUser = await fetch(
          this.origin + "/api/auth/get_user?user_id=" + this.param.userid,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${this.param.token}`,
            },
          }
        );
        let user = await responseUser.json();
        if (user.city) {
          let { latitude, longitude } = user.city;
          this.param.latitude = latitude;
          this.param.longitude = longitude;
          this.param.zoom = 12;
          return true;
        } else {
          console.log("user.city is false");
          return false;
        }
      } catch (error) {
        console.log("catch error", error);
        return false;
      }
    },
    /**
     * Set the application layout.
     *
     * @param {String} layout
     */
  },
};
</script>
