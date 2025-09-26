<template>
  <header class="w-full bg-white dark:bg-gray-900 shadow-md px-6 py-3 flex items-center justify-between">
    <button class="md:hidden p-2 border border-gray-300 dark:border-gray-700 rounded-lg" @click="showMobileNav = !showMobileNav">
      <span class="text-2xl text-gray-700 dark:text-gray-200">☰</span>
    </button>
    <div
      v-if="showMobileNav"
      class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 flex flex-col"
      @click.self="showMobileNav = false"
    >
      <div class="bg-white dark:bg-gray-900 w-64 h-full p-6 shadow-lg">
        <router-link to="/" class="block mb-4 text-gray-700 dark:text-gray-300 font-bold" @click="showMobileNav = false">Home</router-link>
        <router-link to="/categories" class="block mb-4 text-gray-700 dark:text-gray-300" @click="showMobileNav = false">Categories</router-link>
        <router-link
          v-if="auth.isAuthenticated"
          to="/orders"
          class="block mb-4 text-gray-700 dark:text-gray-300"
          @click="showMobileNav = false"
        >Orders</router-link>
        <router-link to="/cart" class="block mb-4 text-gray-700 dark:text-gray-300" @click="showMobileNav = false">Cart</router-link>
        <div class="mt-6">
          <template v-if="!auth.isAuthenticated">
            <router-link to="/auth/login" class="block mb-2 text-gray-700 dark:text-gray-300" @click="showMobileNav = false">Login</router-link>
            <router-link to="/auth/register" class="block mb-2 text-blue-600 dark:text-blue-400" @click="showMobileNav = false">Register</router-link>
          </template>
          <template v-else>
            <span class="block mb-2 text-gray-800 dark:text-gray-200">{{ auth.user?.name }}</span>
            <button @click="logout" class="block w-full text-left mb-2 text-red-500">Logout</button>
          </template>
        </div>
      </div>
    </div>
    
    <router-link to="/" class="text-xl font-bold text-gray-800 dark:text-white">
      Tech Shack
    </router-link>

    <nav class="hidden md:flex space-x-6 items-center">
      <router-link to="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400;">Home</router-link>
      <router-link to="/categories" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400;">Categories</router-link>
      <router-link
        v-if="auth.isAuthenticated"
        to="/orders"
        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400;"
      >Orders</router-link>
    </nav>
    <div class="flex items-center space-x-3">
      <template v-if="!auth.isAuthenticated">
        <router-link to="/auth/login" class="px-3 py-1 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 md:inline hidden">Login</router-link>
        <router-link to="/auth/register" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 md:inline hidden">Register</router-link>
      </template>
      <template v-else>
        <span class="text-gray-800 dark:text-gray-200 md:inline hidden">{{ auth.user?.name }}</span>
        <button @click="logout" class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600 md:inline hidden">Logout</button>
      </template>
      <router-link to="/cart" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
        🛒
      </router-link>
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
const showMobileNav = ref(false);
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
