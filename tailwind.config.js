module.exports = {
  content: [
    './resources/js/**/*.{html,js}',
    './resources/views/**/*.blade.php',
    './packages/takeoffdesigngroup/cms/resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        blue: {
          500: '#004261',
        }
      },
    },
  },
  plugins: [],
}
