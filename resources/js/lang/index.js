import Vue from "vue";
import VueI18n from "vue-i18n";
import Cookies from "js-cookie";
import elementEnLocale from "element-ui/lib/locale/lang/en"; // element-ui lang
import elementEsLocale from "element-ui/lib/locale/lang/es"; // element-ui lang
import elementRuLocale from "element-ui/lib/locale/lang/ru-RU"; // element-ui lang
import enLocale from "./en";
import esLocale from "./es";
import ruLocale from "./ru";

Vue.use(VueI18n);

const messages = {
  en: {
    ...enLocale,
    ...elementEnLocale
  },

  es: {
    ...esLocale,
    ...elementEsLocale
  },
  ru: {
    ...ruLocale,
    ...elementRuLocale
  }
};
export function getLanguage() {
  const chooseLanguage = Cookies.get("language");
  if (chooseLanguage) return chooseLanguage;

  // if has not choose language
  const language = (
    navigator.language || navigator.browserLanguage
  ).toLowerCase();
  const locales = Object.keys(messages);
  for (const locale of locales) {
    if (language.indexOf(locale) > -1) {
      return locale;
    }
  }
  return "en";
}
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
  locale: getLanguage(),
  // set locale messages
  messages
});

export default i18n;
