import i18n from "@/lang";

const title = "Champ Throw Admin";

export default function getPageTitle(key) {
  const hasKey = i18n.te(`${key}`);
  if (hasKey) {
    const pageName = i18n.t(`${key}`);
    return `${pageName} - ${title}`;
  }
  return `${title}`;
}
