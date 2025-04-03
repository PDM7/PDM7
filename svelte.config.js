import adapter from "@sveltejs/adapter-static";

/** @type {import('@sveltejs/kit').Config} */
const config = {
  kit: {
    adapter: adapter(),
    paths: {
      base: process.env.NODE_ENV === "production" ? "/PDM7" : "",
    },
    prerender: {
      entries: ['*'] // Garante que todas as páginas possíveis sejam renderizadas
    },
    alias: {
      $lib: 'src/lib',
      $components: 'src/lib/components'
    }
  },
  strict: true,
};

export default config;
