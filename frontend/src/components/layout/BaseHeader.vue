<template>
  <header class="w-full bg-white dark:bg-gray-900 shadow-md px-6 py-3 flex items-center justify-between">
    <router-link to="/" class="text-xl font-bold text-gray-800 dark:text-white">
      Tech Shack
    </router-link>

    <nav class="hidden md:flex space-x-6">
      <router-link to="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400;">Home</router-link>
      <router-link to="/categories" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400;">Categories</router-link>
      <router-link to="/cart" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400;">Cart</router-link>
    </nav>

    <div class="flex items-center space-x-3">
      <template v-if="!auth.isAuthenticated">
        <router-link to="/auth/login" class="px-3 py-1 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Login</router-link>
        <router-link to="/auth/register" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Register</router-link>
      </template>

      <template v-else>
        <span class="text-gray-800 dark:text-gray-200 mr-2">{{ auth.user?.name }}</span>
        <button @click="logout" class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600">Logout</button>
      </template>

      <button
        @click="toggleDarkMode"
        class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
      >
        <span v-if="isDark">🌙</span>
        <span v-else>☀️</span>
      </button>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "@stores/AuthStore.mjs";

const isDark = ref();
const auth = useAuthStore();

onMounted(() => {
  const saved = localStorage.getItem("theme");
  if (saved === "dark" || (!saved && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
    isDark.value = true;
    document.documentElement.classList.add("dark");
  } else {
    isDark.value = false;
  }

  auth.fetchUser().catch(() => {});
});

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add("dark");
    localStorage.setItem("theme", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    localStorage.setItem("theme", "light");
  }
};

async function logout() {
  await auth.logout();
  window.location.href = "/";
}
</script>
