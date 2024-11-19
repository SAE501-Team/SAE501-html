/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.tpl',    // Vos templates PrestaShop
    './assets/js/**/*.js',     // Vos fichiers JS si vous utilisez du React
  ],
  theme: {
    extend: {
      colors: {
        "be-jaune": "rgb(255 215 0 / <alpha-value>)",
        "be-bleu": "rgb(123 170 247 / <alpha-value>)",
        "be-vert": "rgb(0 230 118 / <alpha-value>)",
        "be-rose": "rgb(255 64 129 / <alpha-value>)",
      },
      borderWidth: {
        3: "3px",
      },
      fontFamily: {
        'adlam': ['"ADLaM Display"', 'sans-serif'], 
        'poppins': ['Poppins', 'sans-serif'],
      },
    },
  },
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}

