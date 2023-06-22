/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./nuxt.config.{js,ts}",
  ],
  theme: {
    extend: {
      colors: {
        "dark": "#0C0D0D",
        "light": "#18181B",
        "rust": "#CD412B",
        "texty": "#828289"
      }
    },
  },
  plugins: [],
}