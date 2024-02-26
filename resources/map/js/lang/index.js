import Vue from "vue";
import VueI18n from "vue-i18n";
import enLocale from "./en";
import esLocale from "./es";
import ruLocale from "./ru";

Vue.use(VueI18n);

const messages = {
  en: {
    ...enLocale,
  },

  es: {
    ...esLocale,
  },
  ru: {
    ...ruLocale,
  },
};

//  type DateTimeHumanReadable = 'long' | 'short' | 'narrow';
//  type DateTimeDigital = 'numeric' | '2-digit';
const dateTimeFormats = {
  en: {
    //  short: {
    //    year: 'numeric',
    //    month: 'short',
    //    day: 'numeric'
    //  },
    time: {
      hour: "numeric",
      minute: "numeric",
      hour12: true,
    },
    // long: {
    //   year: "numeric",
    //   month: "short",
    //   day: "numeric",
    //   weekday: "short",
    //   hour: "numeric",
    //   minute: "numeric",
    //   hour12: true,
    // },
  },
  es: {
    time: {
      hour: "numeric",
      minute: "numeric",
      hour12: true,
    },
  },
  ru: {
    time: {
      hour: "numeric",
      minute: "numeric",
      hour12: false,
    },
  },
};

const i18n = new VueI18n({
  // set locale
  // options: en | zh | es
  dateTimeFormats,
  locale: "en",
  // set locale messages
  messages,
});

export default i18n;
