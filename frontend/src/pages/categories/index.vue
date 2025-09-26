<template>
  <BaseLayout>
    <div class="max-w-5xl mx-auto py-10">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
        {{ $t('pages.categories.title') }}
      </h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="category in categories"
          :key="category.id"
          class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow"
        >
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ category.name }}
          </h2>
          <p class="text-gray-600 dark:text-gray-400 mb-3">
            {{ category.description }}
          </p>
          <router-link
            :to="`/categories/${category.id}`"
            class="text-blue-600 dark:text-blue-400 hover:underline"
          >
            {{ $t('pages.categories.viewProducts') }}
          </router-link>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { http } from "@utils/http.mjs";
import BaseLayout from "@layouts/BaseLayout.vue";

const categories = ref([]);
onMounted(async () => {
  try {
    const { data } = await http.get("/categories");
    categories.value = data;
  } 
  catch (error) {
    console.error("Failed to load categories:", error);
  }
});
</script>
