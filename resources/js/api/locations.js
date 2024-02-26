import request from "@/utils/request";

export function fetchList(filter, page, limit, sort, operator) {
  return request({
    url: "/admin/location/all_locations_pagination",
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

export function createLocation(data) {
  return request({
    url: "/api/location",
    method: "post",
    data,
  });
}
export function updateLocation(id, data) {
  return request({
    url: "/api/location/" + id,
    method: "patch",
    data,
  });
}

export function getLocation(id) {
  return request({
    url: `/api/location/${id}/edit`,
    method: "get",
  });
}
export function updateLocationActivation(data) {
  return request({
    url: "/api/location/activation_update",
    method: "post",
    data,
  });
}
export function getLocationAndAttr(location_id) {
  return request({
    url: `api/location/select_attractions`,
    method: "post",
    params: { location_id },
  });
}
