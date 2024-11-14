// vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// Configuration Vite
export default defineConfig({
  plugins: [react()],
  server: {
    port: 3000, // Change le port si nécessaire
  },
  build: {
    outDir: 'dist', // Dossier de sortie pour le build
  },
});