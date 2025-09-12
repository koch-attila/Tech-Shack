<template>
  <BaseLayout>
    <div class="max-w-3xl mx-auto py-10">
      <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Your Cart</h1>

      <div v-if="cart.items.length === 0" class="text-gray-600 dark:text-gray-400">
        Your cart is empty.
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
              Remove
            </button>
          </div>
        </div>

        <div class="flex justify-between font-bold text-xl mt-6 text-gray-900 dark:text-white">
          <span>Total:</span>
          <span>${{ cart.total }}</span>
        </div>

        <button
          class="mt-6 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg w-full"
          @click="checkout"
        >
          Checkout
        </button>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup>
import { useCartStore } from "@stores/CartStore.mjs";
import { http } from "@utils/http.mjs";
import BaseLayout from "@layouts/BaseLayout.vue";

const cart = useCartStore();

async function removeItem(item){
  if (window.confirm('Are you sure you want to remove this item from the cart?')){
    cart.removeFromCart(item);
  }
}

async function checkout() {
  if (cart.items.length === 0) return;

  try {
    const payload = {
      user_id: 1,
      items: cart.items.map(item => ({
        product_id: item.product.id,
        quantity: item.quantity
      }))
    };

    const response = await http.post('orders', payload);
    const data = response.data;

    console.log('Order created:', data);
    cart.clearCart();

    alert('Order placed successfully!');
  } 
  catch (error) {
    if (error.response) {
      console.error('Server responded with an error:', error);
      alert('Something went wrong during the checkout process. Please try again.');
    } 
    else {
      console.error(error);
      alert('Something went wrong. Please try again.');
    }
  }
}

</script>