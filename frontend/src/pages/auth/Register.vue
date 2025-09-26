<template>
  <BaseHeader />
  <div class="mt-16 flex justify-center bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
      <h1 class="text-2xl font-bold text-center mb-6 text-gray-900 dark:text-white">
        Create an Account
      </h1>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            minlength="8"
            class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            required
            minlength="8"
            class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>

        <div v-if="error" class="text-red-600 dark:text-red-400 text-sm">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
        >
          {{ loading ? "Registering..." : "Register" }}
        </button>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
        Already have an account?
        <router-link to="/login" class="text-blue-600 dark:text-blue-400 hover:underline">
          Log in
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import { useAuthStore } from "@/stores/AuthStore"
import BaseHeader from "@components/layout/BaseHeader.vue"

const router = useRouter()
const auth = useAuthStore()

const form = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
})

const loading = ref(false)
const error = ref(null)

const handleRegister = async () => {
  error.value = null
  loading.value = true
  try {
    await auth.register(form.value)
    router.push("/")
  } catch (err) {
    if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors).flat().join(" ")
    } else {
      error.value = err.response?.data?.message || "Registration failed."
    }
  } finally {
    loading.value = false
  }
}
</script>
