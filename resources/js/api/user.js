import request from "@/utils/request";

export function login(data) {
  return request({
    url: "/api/auth/signin",
    method: "post",
    data,
  });
}

export function setLang(language) {
  return request({
    url: "admin/language/change_language",
    method: "post",
    data: { language },
  });
}
export function getInfo({ token, user_id }) {
  return request({
    url: "api/auth/get_user",
    method: "post",
    params: { user_id },
  });
}
export function getProfile(user_id) {
  if (user_id) {
    return request({
      url: "api/auth/get_user",
      method: "post",
      params: { user_id },
    });
  }
  return request({
    url: "admin/profile/get_profile",
    method: "post",
  });
}
export function signupAdmin(data) {
  return request({
    url: "admin/auth/continue_signup_admin",
    method: "post",
    data,
  });
}
export function payment(data) {
  return request({
    url: "/paypal/payment",
    method: "get",
    params: data,
  });
}

export function logout() {
  return request({
    url: "api/auth/logout",
    method: "get",
  });
}
