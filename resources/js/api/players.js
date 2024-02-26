import request from "@/utils/request";

export function fetchList(filter, page, limit,sort,operator) {
         return request({
           url: "/admin/gamers/all_gamers",
           method: "post",
           params: {
             page: page || 1,
             filter: filter || '',
            //  limit,
             sort: sort || "",
             operator: operator || undefined
           }
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
