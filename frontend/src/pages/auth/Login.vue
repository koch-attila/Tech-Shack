<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Login</h1>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="block mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full border p-2 rounded"
          required
        />
      </div>

      <div class="mb-3">
        <label class="block mb-1">Password</label>
        <input
          v-model="form.password"
          type="password"
          class="w-full border p-2 rounded"
          required
        />
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Login
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { useAuthStore } from "@/stores/AuthStore.mjs";
import { useRouter } from "vue-router";

const auth = useAuthStore();
const router = useRouter();

const form = reactive({
  email: "",
  password: ""
});

async function submit() {
  try {
    await auth.login(form);
    router.push("/");
  } catch (err) {
    alert("Login failed. Please check your credentials.");
  }
}
</script>
