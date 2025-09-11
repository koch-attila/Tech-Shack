<template>
  <BaseLayout />
  <div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <BaseCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { http } from "@utils/http.mjs"
import BaseCard from "@components/BaseCard.vue"
import BaseLayout from "@layouts/BaseLayout.vue"

const products = ref([])

onMounted(async () => {
  try {
    const { data } = await http.get("/products")
    products.value = data
  } 
  catch (error) {
    console.error("Failed to load products:", error)
  }
})
</script>
