const getters = {
  sidebar: (state) => state.app.sidebar,
  device: (state) => state.app.device,
  token: (state) => state.user.token,
  user_id: (state) => state.user.user_id,
  language: (state) => state.app.language,
  roles: (state) => state.user.roles,
  permission_routes: (state) => state.permission.routes,
  name: (state) => state.user.name,
};
export default getters
