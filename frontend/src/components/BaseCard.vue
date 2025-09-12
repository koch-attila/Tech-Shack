<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4 transition hover:shadow-lg"
  >
    <router-link
      :to="`/products/${product.id}`"
    >
      <img
        v-if="product.image"
        :src="`${baseUrl}/storage/products/${product.image}`"
        :alt="product.name"
        class="h-40 w-full object-contain rounded-xl mb-3 bg-white"
      />
      <h2 class="text-lg text-black dark:text-white font-semibold">{{ product.name }}</h2>
      <p class="text-sm text-gray-500 dark:text-gray-100 mb-2">{{ product.description }}</p>
      <p class="text-xl text-black dark:text-white font-bold mb-3">${{ product.price }}</p>
    </router-link>
  </div>
</template>

<script setup>
import { getCurrentInstance } from "vue";
const { appContext } = getCurrentInstance();

defineProps({
  product: {
    type: Object,
    required: true
  }
})

const baseUrl = appContext.config.globalProperties.$backendUrl;
</script>
