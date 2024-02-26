import request from "@/utils/request";
export function fetchList(add_filter,filter, page, sort, operator) {
         return request({
           url: "/admin/license/all_licenses",
           method: "post",
           params: {
             page: page || 1,
             filter: filter || "",
             add_filter: add_filter || "",
             sort: sort || "",
             operator: operator || undefined,
           },
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
