<template>
  <div class="wrappper">
    <div ref="googleMap" class="google-map"></div>
    <div ref="popup-container" v-if="isOpen" class="popup-container">
      <div class="address-modal">
        <div class="address-modal__container">
          <button
            type="button"
            class="address-modal__btn"
            @click="onClose"
            title="Close"
            aria-label="Close"
          ></button>
          <div class="address-modal__info address-info">
            <div class="address-info__picture pic-overlay">
              <img :src="selectLoc.image" />
            </div>
            <div class="address-info__brief address-brief">
              <h2 class="address-brief__title">
                {{ selectLoc.title || $t("noData") }}
              </h2>
              <div class="address-frief__list">
                <div
                  v-if="selectLoc.rating"
                  class="address-brief__item address-brief__item--stars"
                >
                  {{ selectLoc.rating }}
                </div>
                <div
                  v-if="selectLoc.address"
                  class="address-brief__item address-brief__item--address"
                >
                  {{ selectLoc.address }}
                </div>
                <div
                  v-if="selectLoc.working_hours"
                  class="address-brief__item address-brief__item--time"
                >
                  {{ selectLoc.working_hours }}
                </div>
                <div class="address-brief__item address-brief__item--schedule">
                  {{ `${selectLoc.visit ? $t("visited") : $t("novisited")}` }}
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="address-modal__achievements address-achievements"> -->
          <!-- <h3 class="address-achievements__header">Achievements</h3> -->
          <!-- <div class="address-achievements__pics">
          <div class="address-achievements__pic pic-overlay">
            <img src="img2.jpg" srcset="img2.jpg 1x, img2@2x.jpg 2x">
          </div>

          <div class="address-achievements__pic pic-overlay">
            <img src="img3.jpg" srcset="img.jpg 1x, img3@2x.jpg 2x">
          </div>

          <div class="address-achievements__pic pic-overlay">
            <img src="img4.jpg" srcset="img.jpg 1x, img4@2x.jpg 2x">
          </div>

          <div class="address-achievements__pic pic-overlay">
            <img src="img5.jpg" srcset="img.jpg 1x, img5@2x.jpg 2x">
          </div>

          <div class="address-achievements__pic pic-overlay">
            <img src="img6.jpg" srcset="img.jpg 1x, img6@2x.jpg 2x">
          </div>
        </div> -->
          <!-- </div> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import createHTMLMapMarker from "./custom-marker.js";
export default {
  name: "GoogleMap",
  props: {
    param: {
      type: [Array, Object],
      required: true,
    },
    locations: {
      type: [Array, Object],
      required: true,
    },
  },
  data() {
    return {
      // bounds: new google.maps.LatLngBounds(),
      isOpen: false,
      selectLoc: {},
      mapOptions: {
        // center: { lat: -77.397, lng: 140.644 },
        disableDefaultUI: true,
        clickableIcons: false,
        zoom: 12,
        gestureHandling: "cooperative",
      },
    };
  },

  mounted() {
    this.initMap();
  },
  methods: {
    onClose() {
      this.isOpen = false;
      this.selectLoc = {};
    },
    initMap() {
      let { origin } = window.location;
      const locations = this.locations;
      const { latitude, longitude, zoom } = this.param;
      const map = new google.maps.Map(this.$refs.googleMap, {
        ...this.mapOptions,
        zoom: zoom && Number.isInteger(zoom) ? zoom : this.mapOptions.zoom,
        center: new google.maps.LatLng(
          latitude.toString().replace(",", "."),
          longitude.toString().replace(",", ".")
        ),
      });
      locations.forEach((location) => {
        location.image = location.url_avatar
          ? origin + location.url_avatar
          : window.location.origin + "/images/logo-big.png";
        const marker = new createHTMLMapMarker({
          latlng: new google.maps.LatLng(location.lat, location.lng),
          map: map,
          html: `<div title="${location.title}" class="border-marker${
            location.visit ? " loc-visit" : ""
          }"><img class="image-marker" src="${location.image}"></div>`,
        });

        google.maps.event.addListener(marker, "click", () => {
          let loc = { ...location };
          this.isOpen = true;
          this.selectLoc.image = loc.image;
          this.selectLoc.visit = loc.visit;
          this.selectLoc.rating = loc.rating;
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
            hours = `${open} ${this.$t("to")} ${close}`;
          }
          this.selectLoc.working_hours = hours;
          this.selectLoc.address = `${
            loc && loc.region ? loc.region.name + ", " : ""
          }${loc.country && loc.country.name ? loc.country.name + ", " : ""}${
            loc.city && loc.city.name ? loc.city.name + ", " : ""
          }${loc && loc.street ? loc.street : ""}`;
          this.selectLoc.title = loc.title;
          this.$nextTick(() => {
            let popup = new Popup(marker.latlng, this.$refs["popup-container"]);
            popup.setMap(map);
          });
        });
        return marker;
      });

      // create MarkerClusterer and add Image
      // let markerCluster = new MarkerClusterer(
      //   map,
      //   markers,

      //   { imagePath: imgClusterUrl }
      // );

      // авто масштабирование
      // map.fitBounds(this.bounds);

      class Popup extends google.maps.OverlayView {
        constructor(position, content) {
          super();
          this.position = position;
          this.containerDiv = content;

          // Optionally stop clicks, etc., from bubbling up to the map.
          Popup.preventMapHitsAndGesturesFrom(this.containerDiv);
        }
        hide() {
          if (this.div) {
            this.containerDiv.style.visibility = "hidden";
          }
        }

        show() {
          if (this.div) {
            this.containerDiv.style.visibility = "visible";
          }
        }

        onAdd() {
          this.getPanes().floatPane.appendChild(this.containerDiv);
        }
        onRemove() {}
        /** Called each frame when the popup needs to draw itself. */
        draw() {
          const divPosition = this.getProjection().fromLatLngToDivPixel(
            this.position
          );
          // Hide the popup when it is far out of view.
          const display =
            Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000
              ? "block"
              : "none";

          if (display === "block") {
            this.containerDiv.style.left = divPosition.x + "px";
            this.containerDiv.style.top = divPosition.y + "px";
          }

          if (this.containerDiv.style.display !== display) {
            this.containerDiv.style.display = display;
          }
        }
      }
      // let popup = new Popup(
      //   new google.maps.LatLng(-31.563944, 147.154355),
      //   this.$refs["popup-container"]
      // );
      // popup.setMap(map);
    },
  },
};
</script>
