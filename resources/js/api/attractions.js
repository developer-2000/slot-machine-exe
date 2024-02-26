import request from "@/utils/request";
export function fetchList(filter, page, limit, sort, operator) {
  return request({
    url: "/admin/attraction/all_attractions_user",
    method: "post",
    params: {
      page: page || 1,
      filter: filter || undefined,
      //  limit,
      sort: sort || "",
      operator: operator || undefined,
    },
  });
}

export function fetchAttractions(location_id) {
  return request({
    url: "/api/location/select_attractions",
    method: "post",
    params: { location_id },
  });
}
export function updateAttractionsActivation(data) {
         return request({
           url: "/api/attraction/activation_update",
           method: "post",
           data,
         });
       }
// export function fetchPv(pv) {
//   return request({
//     url: "/vue-element-admin/article/pv",
//     method: "get",
//     params: { pv }
//   });
// }

export function getAttraction(id,admin) {
  return request({
    url: `/api/attraction/${id}/edit`,
    method: "get",
    params: { attraction_id: id, admin_id: admin },
  });
}
export function createAttraction(data) {
  return request({
    url: "/admin/attraction/create_attraction",
    method: "post",
    data,
  });
}

export function updateAttraction(id, data) {
  return request({
    url: "/api/attraction/" + id,
    method: "patch",
    data,
  });
}
// export function updateArticle(data) {
//   return request({
//     url: "/vue-element-admin/article/update",
//     method: "post",
//     data
//   });
// }
