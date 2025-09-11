/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
      "./index.html",
      "./src/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
      extend: {},
    },
    plugins: [
        import('@tailwindcss/aspect-ratio'),
        import('@tailwindcss/container-queries'),
        import('@tailwindcss/forms'),
        import('@tailwindcss/typography')
    ],
  }