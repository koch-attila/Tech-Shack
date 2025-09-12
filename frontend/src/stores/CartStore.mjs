import { defineStore } from "pinia";

export const useCartStore = defineStore("cart", {
  state: () => ({
    items: [],
  }),
  getters: {
    total: (state) =>
      state.items.reduce((sum, i) => sum + i.product.price * i.quantity, 0),
    count: (state) =>
      state.items.reduce((sum, i) => sum + i.quantity, 0),
  },
  actions: {
    addToCart(product, quantity = 1) {
      const existing = this.items.find((i) => i.product.id === product.id);
      if (existing) {
        existing.quantity += quantity;
      } else {
        this.items.push({ product, quantity });
      }
    },
    updateQuantity(productId, quantity) {
      const item = this.items.find(i => i.product.id === productId);
      if (item && quantity >= 1) {
        item.quantity = quantity;
      }
    },
    removeFromCart(productId) {
      this.items = this.items.filter((i) => i.product.id !== productId);
    },
    clearCart() {
      this.items = [];
    },
  },
});
