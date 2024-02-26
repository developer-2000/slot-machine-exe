<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item name="count">
      <template slot="title">

        <img   :src="require('@/assets/logo/logo.png').default" class="profile-attractions__img"/>

        <h2 class="profile-attractions__location">LocationName</h2>

        <p class="profile-attractions__address">Kansas state country, street, house number</p>

        <ul class="profile-attractions__qty-list">
          <li class="profile-attractions__qty-item">Дневной: 999</li>
          <li class="profile-attractions__qty-item">Месячный: 999</li>
          <li class="profile-attractions__qty-item">Все вермя: 999</li>
        </ul>
      </template>
      <div class="profile-attractions__item profile-attraction"
           v-for="index in 7" :key="index">
        <h3 class="profile-attraction__title">Attraction</h3>
        <p class="profile-attraction__uid">uid: <span class="uid">123456789</span></p>
      </div>
    </el-collapse-item>
  </el-collapse>
</template>
<script>
  export default {
    name: 'Attractions',
    data() {
      return {
        activeName: '1',
        count: '1'
      };
    }
  }
</script>
