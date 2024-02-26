import request from "@/utils/request";

export function fetchList(filter, page, limit, sort, operator) {
  return request({
    url: "/admin/clients/get_clients",
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

export function fetchCountry(code) {
  return request({
    url: "/api/load_country",
    method: "post",
    params: { code: code || undefined },
  });
}
export function fetchRegions(code) {
  return request({
    url: "/api/load_regions",
    method: "post",
    params: { code: code || undefined },
  });
}
export function fetchCities(code) {
  return request({
    url: "/api/load_cities",
    method: "post",
    params: { code: code || undefined },
  });
}

// export function fetchPv(pv) {
//   return request({
//     url: "/vue-element-admin/article/pv",
//     method: "get",
//     params: { pv }
//   });
// }

export function createClient(data) {
  return request({
    url: "/admin/auth/signup_admin",
    method: "post",
    data,
  });
}
export function updateClientActivation(data) {
  return request({
    url: "/api/auth/activation_update",
    method: "post",
    data,
  });
}
export function updateClient(data) {
  return request({
    url: "/admin/auth/update_admin",
    method: "post",
    data
  });
}
