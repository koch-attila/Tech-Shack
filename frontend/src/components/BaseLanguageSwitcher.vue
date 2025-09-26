<template>
  <button
    class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 text-2xl"
    @click="toggleLocale"
    :aria-label="locale === 'en' ? 'Switch to Hungarian' : 'Switch to English'"
  >
    <span v-if="locale === 'en'">🇬🇧</span>
    <span v-else>🇭🇺</span>
  </button>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { locale } = useI18n()
function toggleLocale() {
  locale.value = locale.value === 'en' ? 'hu' : 'en'
}
</script>