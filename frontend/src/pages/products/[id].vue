<template>
  <BaseLayout>
    <div v-if="product" class="max-w-4xl mx-auto py-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <img
          :src="`${baseUrl}/storage/products/${product.image}`"
          :alt="product.name"
          class="w-full h-80 object-contain rounded-lg shadow"
        />

        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ product.name }}
          </h1>
          <p class="mt-4 text-lg text-gray-700 dark:text-gray-300">
            {{ product.description }}
          </p>
          <p class="mt-6 text-2xl font-semibold text-blue-600 dark:text-blue-400">
            ${{ product.price }}
          </p>
          <button
            class="mt-6 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
            @click="addToCart"
          >
            Add to Cart
          </button>
          <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
              Categories
            </h2>
            <ul class="flex flex-wrap gap-2">
              <li
                v-for="category in categories"
                :key="category.id"
              >
                <router-link
                  :to="`/categories/${category.id}`"
                  class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-full text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                  {{ category.name }}
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="my-12">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
        Reviews
      </h2>

      <div v-if="!reviews.length" class="text-gray-600 dark:text-gray-400">
        No reviews yet.
      </div>

      <ul v-else class="space-y-6">
        <li
          v-for="review in reviews"
          :key="review.id"
          class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
        >
          <div class="flex items-center justify-between">
            <span class="font-semibold text-gray-800 dark:text-gray-200">
              {{ review.user.name || "Anonymous" }}
            </span>
            <span class="text-yellow-500">
              {{ '★'.repeat(Number(review.rating) || 0) }}{{ '☆'.repeat(5 - (Number(review.rating) || 0)) }}
            </span>
          </div>
          <p class="mt-2 text-gray-700 dark:text-gray-300">
            {{ review.comment || "No comment provided." }}
          </p>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ review.created_at ? new Date(review.created_at).toLocaleDateString() : "Unknown date" }}
          </p>
        </li>
      </ul>
    </div>
  </BaseLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { http } from "@utils/http.mjs";
import { useCartStore } from "@stores/CartStore.mjs";
import BaseLayout from "@layouts/BaseLayout.vue";
import { getCurrentInstance } from "vue";

const { appContext } = getCurrentInstance();
const baseUrl = appContext.config.globalProperties.$backendUrl;

const route = useRoute();
const product = ref();
const reviews = ref([]);
const categories = ref([]);
const cart = useCartStore();

function addToCart() {
  if (product.value) {
    cart.addToCart(product.value);
    alert("Product added to cart!");
  }
}

onMounted(async () => {
  try {
    const { data } = await http.get(`/products/${route.params.id}`);
    product.value = data;

    const reviewsRes = await http.get(`/products/${route.params.id}/ratings`);
    console.log("reviews API response:", reviewsRes.data);
    reviews.value = Array.isArray(reviewsRes.data) ? reviewsRes.data : [];
    const categoriesRes = await http.get(`/products/${route.params.id}/categories`);
    categories.value = categoriesRes.data;
  } 
  catch (error) {
    console.error("Failed to load product or reviews:", error);
  }
});
</script>
