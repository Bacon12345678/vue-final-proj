<template>
  <form @submit.prevent="login">
    <label for="email">email</label>
    <input type="text" v-model="email" id="email" />
    <label for="password">password </label>
    <input type="password" v-model="password" />
    <button>登入</button>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const email = ref<string | null>(null);

const password = ref<string | null>(null);

const login = async () => {
  const res = await fetch('http://127.0.0.1/api/login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      email: email.value,
      password: password.value,
    }),
  });

  const data = await res.json();

  if (data.token) {
    localStorage.setItem('jwt_token', data.token);
    alert('登入成功！');
    // 可導向首頁或刷新頁面
  } else {
    alert('登入失敗！');
  }
};
</script>
