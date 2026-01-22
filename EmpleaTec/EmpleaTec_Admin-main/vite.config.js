import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: [{ find: "@", replacement: "/src" }],
  },
  server: {
    host: "0.0.0.0",  // Esto hará que Vite escuche en todas las interfaces de red
    port: 5173,        // Puedes mantener el puerto o cambiarlo si es necesario
  },
});
