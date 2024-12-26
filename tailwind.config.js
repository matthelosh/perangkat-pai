/** @type {import('tailwindcss').Config} */
export default {
  content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './node_modules/element-plus/**/*.js',
      ],
  theme: {
    extend: {},
  },
  plugins: [],
  corePlugins: {
    preflight: false,
  },
  safelist: [
    {
      pattern: /el-.*/,
    }
  ]
}

