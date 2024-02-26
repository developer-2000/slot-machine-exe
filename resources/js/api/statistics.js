import request from "@/utils/request";

export function fetchStatisticGamer() {
  return request({
    url: "/admin/statistic/gamer_statistic",
    method: "post"
  });
}
export function fetchStatisticLocation() {
  return request({
    url: "/admin/statistic/location_statistic",
    method: "post"
  });
}

// export function fetchArticle(id) {
//   return request({
//     url: "/vue-element-admin/article/detail",
//     method: "get",
//     params: { id }
//   });
// }

// export function fetchPv(pv) {
//   return request({
//     url: "/vue-element-admin/article/pv",
//     method: "get",
//     params: { pv }
//   });
// }

// export function createArticle(data) {
//   return request({
//     url: "/vue-element-admin/article/create",
//     method: "post",
//     data
//   });
// }

// export function updateArticle(data) {
//   return request({
//     url: "/vue-element-admin/article/update",
//     method: "post",
//     data
//   });
// }
