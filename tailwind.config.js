/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
      ],
  theme: {
    extend: {
        backgroundImage: {
            'lgt': "url('background/bg1.png')",
            'drk': "url('background/dark.png')",
          }
    },
  },
  plugins: [],
}

