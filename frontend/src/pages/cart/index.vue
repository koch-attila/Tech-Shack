<template>
  <BaseLayout>
    <div class="max-w-3xl mx-auto py-10">
      <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">{{ $t('pages.cart.title') }}</h1>

      <div v-if="cart.items.length === 0" class="text-gray-600 dark:text-gray-400">
        {{ $t('pages.cart.empty') }}
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="item in cart.items"
          :key="item.product.id"
          class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4"
        >
          <div>
            <p class="font-semibold text-gray-900 dark:text-white">
              {{ item.product.name }}
            </p>
            <p class="text-gray-600 dark:text-gray-400">
              ${{ item.product.price }} × {{ item.quantity }}
            </p>
          </div>
          <div class="flex items-center space-x-2">
            <div class="flex border rounded-lg overflow-hidden mr-2">
              <button
                :disabled="item.quantity === 1"
                @click="cart.updateQuantity(item.product.id, item.quantity - 1)"
                class="px-3 py-1 dark:text-white bg-white dark:bg-blue-900 disabled:bg-gray-300 disabled:dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-blue-700 transition"
              >
                -
              </button>
              <div class="px-4 py-1 bg-gray-100 dark:bg-blue-800 text-gray-900 dark:text-white flex items-center justify-center min-w-[2rem]">
                {{ item.quantity }}
              </div>
              <button
                @click="cart.updateQuantity(item.product.id, item.quantity + 1)"
                class="px-3 py-1 dark:text-white bg-white dark:bg-blue-900 hover:bg-gray-100 dark:hover:bg-blue-700 transition"
              >
                +
              </button>
            </div>
            <button
              class="text-white bg-red-500 p-3 rounded-lg hover:text-red-700 ml-5"
              @click="removeItem(item.product.id)"
            >
              {{ $t('pages.cart.remove') }}
            </button>
          </div>
        </div>

        <div class="flex justify-between font-bold text-xl mt-6 text-gray-900 dark:text-white">
          <span>{{ $t('pages.cart.total') }}:</span>
          <span>${{ cart.total }}</span>
        </div>

        <form @submit.prevent="checkout" class="mt-6">
          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.name') }}</label>
            <input
              v-model="form.name"
              required
              class="form-input"
              :placeholder="$t('pages.cart.name')"
            />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.email') }}</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="form-input"
              :placeholder="$t('pages.cart.email')"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.delivery_address') }}</label>
            <input
              v-model="form.delivery_address"
              required
              class="form-input"
              :placeholder="$t('pages.cart.delivery_address')"
            />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.delivery_city') }}</label>
            <input
              v-model="form.delivery_city"
              required
              class="form-input"
              :placeholder="$t('pages.cart.delivery_city')"
            />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.delivery_postal_code') }}</label>
            <input
              v-model="form.delivery_postal_code"
              required
              class="form-input"
              :placeholder="$t('pages.cart.delivery_postal_code')"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">
              <input type="checkbox" v-model="showBilling" />
                {{ $t('pages.cart.use_different_billing') }}
            </label>
          </div>

          <div v-if="showBilling">
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.billing_address') }}</label>
              <input
                v-model="form.billing_address"
                required
                class="form-input"
                :placeholder="$t('pages.cart.billing_address')"
              />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.billing_city') }}</label>
              <input
                v-model="form.billing_city"
                required
                class="form-input"
                :placeholder="$t('pages.cart.billing_city')"
              />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.billing_postal_code') }}</label>
              <input
                v-model="form.billing_postal_code"
                required
                class="form-input"
                :placeholder="$t('pages.cart.billing_postal_code')"
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300">{{ $t('pages.cart.phone') }}</label>
            <input
              v-model="form.phone"
              required
              class="form-input"
              :placeholder="$t('pages.cart.phone')"
            />
          </div>
          <button
            type="submit"
            class="btn btn-success w-full px-6 py-3 rounded-lg"
          >
            {{ $t('pages.cart.checkout') }}
          </button>
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useAuthStore } from "@stores/AuthStore.mjs";
import { useCartStore } from "@stores/CartStore.mjs";
import { http } from "@utils/http.mjs";
import BaseLayout from "@layouts/BaseLayout.vue";

const auth = useAuthStore();
const cart = useCartStore();

const form = ref({
  name: "",
  email: "",
  billing_address: "",
  billing_city: "",
  billing_postal_code: "",
  delivery_address: "",
  delivery_city: "",
  delivery_postal_code: "",
  phone: ""
});
const showBilling = ref(false);

onMounted(() => {
  if (auth.user) {
    form.value.name = auth.user.name || "";
    form.value.email = auth.user.email || "";
    form.value.phone = auth.user.phone || "";
    form.value.delivery_address = auth.user.delivery_address || "";
    form.value.delivery_city = auth.user.delivery_city || "";
    form.value.delivery_postal_code = auth.user.delivery_postal_code || "";
    
    if (showBilling.value) {
      form.value.billing_address = auth.user.billing_address || "";
      form.value.billing_city = auth.user.billing_city || "";
      form.value.billing_postal_code = auth.user.billing_postal_code || "";
    }
  }
});

watch(() => auth.user, (user) => {
  if (user) {
    form.value.name = user.name || "";
    form.value.email = user.email || "";
    form.value.phone = user.phone || "";
    form.value.delivery_address = user.delivery_address || "";
    form.value.delivery_city = user.delivery_city || "";
    form.value.delivery_postal_code = user.delivery_postal_code || "";
    if (showBilling.value) {
      form.value.billing_address = user.billing_address || "";
      form.value.billing_city = user.billing_city || "";
      form.value.billing_postal_code = user.billing_postal_code || "";
    }
  }
});

watch(
  () => [form.value.delivery_address, form.value.delivery_city, form.value.delivery_postal_code, showBilling.value],
  ([address, city, postal, billingShown]) => {
    if (!billingShown) {
      form.value.billing_address = address;
      form.value.billing_city = city;
      form.value.billing_postal_code = postal;
    }
  }
);

async function removeItem(productId){
  if (window.confirm($t('pages.cart.remove') + '?')){
    cart.removeFromCart(productId);
  }
}

async function checkout() {
  if (cart.items.length === 0) return;

  if (!showBilling.value) {
    form.value.billing_address = form.value.delivery_address;
    form.value.billing_city = form.value.delivery_city;
    form.value.billing_postal_code = form.value.delivery_postal_code;
  }

  try {
    const payload = {
      ...form.value,
      items: cart.items.map(item => ({
        product_id: item.product.id,
        quantity: item.quantity
      }))
    };
    await http.post("/orders", payload);
    cart.clearCart();
    alert($t('pages.cart.checkout') + " " + $t('pages.cart.success'));
  } catch (error) {
    alert($t('pages.cart.checkout') + " " + $t('pages.cart.failed') + ": " + (error.response?.data?.message || error.message));
  }
}
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.btn-success {
  @apply bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-green-700 transition;
}
</style>