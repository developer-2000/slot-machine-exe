<template>
  <div :class="{ 'has-logo': showLogo }">
    <logo :collapse="isCollapse" />
    <el-menu
      :default-active="activeMenu"
      :collapse="isCollapse"
      :background-color="'linear-gradient(360deg, #C8EF62 0%, #268597 50.52%, #116272 100%)'"
      :text-color="'#bfcbd9'"
      :unique-opened="false"
      :active-text-color="'#FFF'"
      :collapse-transition="false"
      mode="vertical"
    >
      <sidebar-item
        v-for="(route, i) in permission_routes"
        :key="i"
        :item="route"
        :base-path="route.path"
      />
    </el-menu>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Logo from './Logo'
import SidebarItem from './SidebarItem'

export default {
  components: { SidebarItem, Logo },
  computed: {
    ...mapGetters(['permission_routes',"sidebar"]),
    activeMenu() {
      const route = this.$route;
      const { meta, path } = route;
      // if set path, the sidebar will highlight the path you set
      if (meta.activeMenu) {
        return meta.activeMenu;
      }
      return path;
    },
    showLogo() {
      return this.$store.state.settings.sidebarLogo;
    },
    isCollapse() {
      return !this.sidebar.opened;
    },
  },
};
</script>
