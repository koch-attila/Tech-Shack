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
        if (error.response?.status === 419) {
          await http.get('/sanctum/csrf-cookie', { baseURL: "http://backend.vm1.test", withCredentials: true });
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
      function deleteCookie(name, domain = '.vm1.test') {
        document.cookie = `${name}=; Max-Age=0; path=/; domain=${domain};`;
      }

      try {
        await http.get('/sanctum/csrf-cookie', { baseURL: "http://backend.vm1.test", withCredentials: true });
        await http.post("/auth/logout");
      } catch (error) {
        if (error.response?.status === 419) {
          deleteCookie('tech_shack_session');
          deleteCookie('XSRF-TOKEN');
          this.user = null;
          return;
        }
        throw error;
      }
      deleteCookie('tech_shack_session');
      deleteCookie('XSRF-TOKEN');
      this.user = null;
    },
  },
  persist: true,
});
