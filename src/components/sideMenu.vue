<template>
  <div>
    <ul v-for="menu in menuList">
      <router-link :to="menu.url">
        <li>{{ menu.title }}</li>
      </router-link>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { sideMenu } from '@/data/sideMenu';

const menuList = sideMenu;
</script>
