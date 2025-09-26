<template>
  <BaseHeader />
  <div class="mt-16 flex justify-center bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
      <h1 class="text-2xl font-bold text-center mb-6 text-gray-900 dark:text-white">
        Login
      </h1>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>
        <div v-if="error" class="text-red-600 dark:text-red-400 text-sm">
          {{ error }}
        </div>
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
        >
          Login
        </button>
      </form>
      <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
        Don't have an account?
        <router-link to="/register" class="text-blue-600 dark:text-blue-400 hover:underline">
          Register
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useAuthStore } from "@/stores/AuthStore.mjs";
import { useRouter } from "vue-router";
import BaseHeader from "@components/layout/BaseHeader.vue";

const auth = useAuthStore();
const router = useRouter();

const form = reactive({
  email: "",
  password: ""
});
const error = ref(null);

async function submit() {
  error.value = null;
  try {
    await auth.login(form);
    router.push("/");
  } catch (err) {
    error.value = "Login failed. Please check your credentials.";
  }
}
</script>
