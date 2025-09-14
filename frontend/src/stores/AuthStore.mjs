import { defineStore } from "pinia";
import { http } from "@utils/http.mjs";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
  actions: {
    async register(payload) {
      try {
        await http.get('/sanctum/csrf-cookie', { baseURL: "http://backend.vm1.test", withCredentials: true });
        await http.post("/auth/register", payload);
        await this.fetchUser();
        return true;
      } catch (error) {
        throw error;
      }
    },
    async login(payload) {
      try {
        await http.get('/sanctum/csrf-cookie', { baseURL: "http://backend.vm1.test", withCredentials: true });
        await http.post("/auth/login", payload);
        await this.fetchUser();
        return true;
      } catch (error) {
        throw error;
      }
    },
    async fetchUser() {
      try {
        const { data } = await http.get("/auth/me");
        this.user = data;
      } catch {
        this.user = null;
      }
    },
    async logout() {
      await http.post("/auth/logout");
      this.user = null;
    },
  },
  persist: true,
});
