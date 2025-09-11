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
      
    <button
      @click="toggleDarkMode"
      class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
    >
      <span v-if="isDark">🌙</span>
      <span v-else>☀️</span>
    </button>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";

const isDark = ref();

onMounted(() => {
  const saved = localStorage.getItem("theme");
  if (saved === "dark" || (!saved && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
    isDark.value = true;
    document.documentElement.classList.add("dark");
  }
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
</script>