/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.html",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      fontFamily:{
        marko:['Marko One, serif;'],
        inria:['Inria Serif'],
        inter:['inter'],
        jsMath:['jsMath-cmr10']
      },
      backgroundImage:{
        'bground': "url('/public/img/bground.jpg')",
        'bground1':"url('/public/img/feryy.jpg')",
        'logo': "url('/public/img/logo.jpg')"
      },
      animation: {
				fade: 'fadeIn 1.1s ease-in-out',
			},

			keyframes: {
				fadeIn: {
					from: { opacity: 0 },
					to: { opacity: 1 },
				},
      },
    },
  },
  plugins: [require("tw-elements/plugin.cjs"),
  require('flowbite/plugin')],
}