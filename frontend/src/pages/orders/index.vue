<template>
  <BaseLayout>
    <div class="max-w-3xl mx-auto py-10">
      <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">My Orders</h1>
      <div v-if="orders.length === 0" class="text-gray-600 dark:text-gray-400">
        You have no orders yet.
      </div>
      <div v-else class="space-y-6">
        <div
          v-for="order in orders"
          :key="order.id"
          class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow"
        >
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg text-gray-800 dark:text-gray-200">
              Order #{{ order.id }}
            </span>
            <span class="text-sm text-gray-500 dark:text-gray-400">
              {{ new Date(order.created_at).toLocaleDateString() }}
            </span>
          </div>
          <div class="mb-2">
            <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
            <span class="ml-2 text-blue-600 dark:text-blue-400">{{ order.status }}</span>
          </div>
          <div class="mb-2">
            <span class="font-medium text-gray-700 dark:text-gray-300">Total:</span>
            <span class="ml-2 text-green-600 dark:text-green-400 font-semibold">
              ${{ order.total }}
            </span>
          </div>
          <div class="mb-2">
            <span class="font-medium text-gray-700 dark:text-gray-300">Delivery Address:</span>
            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ order.delivery_address }}, {{ order.delivery_city }} {{ order.delivery_postal_code }}</span>
          </div>
          <div>
            <span class="font-medium text-gray-700 dark:text-gray-300">Items:</span>
            <ul class="list-disc ml-6 mt-1">
              <li
                v-for="item in order.items"
                :key="item.id"
                class="text-gray-700 dark:text-gray-200"
              >
                {{ item.product?.name || 'Product' }} x {{ item.quantity }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { http } from "@utils/http.mjs";
import BaseLayout from "@layouts/BaseLayout.vue";

const orders = ref([]);

onMounted(async () => {
  try {
    const { data } = await http.get("/orders");
    orders.value = Array.isArray(data) ? data : [];
  } catch (error) {
    orders.value = [];
  }
});
</script>