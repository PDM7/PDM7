import { sveltekit } from '@sveltejs/kit/vite';

/** @type {import('vite').UserConfig} */
const config = {
  plugins: [sveltekit()],
  server: {
    allowedHosts: [
      '5421-2804-30f4-106-fa00-696d-5175-da39-9379.ngrok-free.app',
    ]
  }
};

export default config;