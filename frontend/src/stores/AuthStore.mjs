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
    deleteCookie(name, domain = '.vm1.test') {
      document.cookie = `${name}=; Max-Age=0; path=/; domain=${domain};`;
    },

    async register(payload) {
      try {
        await http.post("/auth/register", payload);
        await this.fetchUser();
        return true;
      } catch (error) {
        if (error.response?.status === 419) {
          await http.post("/auth/register", payload);
          await this.fetchUser();
          return true;
        }
        throw error;
      }
    },
    async login(payload) {
      try {
        await http.post("/auth/login", payload);
        await this.fetchUser();
        return true;
      } catch (error) {
        if (error.response?.status === 419) {
          await http.post("/auth/login", payload);
          await this.fetchUser();
          return true;
        }
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
      try {
        await http.post("/auth/logout");
      } catch (error) {
        this.user = null;
        return;
      }
      this.user = null;
    },
  },
  persist: true,
});
