<template>
  <BaseLayout>
    <div v-if="category" class="max-w-6xl mx-auto py-10">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
          {{ category.name }}
        </h1>
      </div>

      <div v-if="!products.length" class="text-gray-600 dark:text-gray-400">
        No products found in this category.
      </div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow hover:shadow-lg transition"
        >
          <router-link :to="`/products/${product.id}`">
            <img
              :src="`${baseUrl}/storage/products/${product.image}`"
              :alt="product.name"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                {{ product.name }}
              </h3>
              <p class="mt-2 text-blue-600 dark:text-blue-400 font-semibold">
                ${{ product.price }}
              </p>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { http } from "@utils/http.mjs";
import BaseLayout from "@layouts/BaseLayout.vue";
import { getCurrentInstance } from "vue";

const { appContext } = getCurrentInstance();
const baseUrl = appContext.config.globalProperties.$backendUrl;

const route = useRoute();
const category = ref();
const products = ref([]);

onMounted(async () => {
  try {
    const { data: categoryData } = await http.get(`/categories/${route.params.id}`);
    category.value = categoryData;

    const { data: productsData } = await http.get(`/categories/${route.params.id}/products`);
    products.value = Array.isArray(productsData) ? productsData : [];
  } 
  catch (error) {
    console.error("Failed to load category or products:", error);
  }
});
</script>
