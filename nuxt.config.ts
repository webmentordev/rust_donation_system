// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: [
    '@nuxtjs/tailwindcss',
    'nuxt-icon',
    '@nuxt/image'
  ],
  ssr: false,
  runtimeConfig: {
    public: {
      url: "http://localhost:8000",
    }
  },
  app: {
    head: {
      title: 'Rust Webstore — RustyUranium',
      link: [
        {
          rel: 'shortcut icon',
          href: "https://files.facepunch.com/lewis/1b2911b1/rust-marque.svg"
        }
      ]
    }
  }
})