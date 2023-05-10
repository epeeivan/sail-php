/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode:"class",
  content: ["./system/view/**/*.{html,js,php}"],
  // content: ["./app/**/*.{html,js,php}"],
  theme: {
    extend: {
      keyframes: {
        rotateimg: {
          '0%': { transform: 'rotate(0deg)' },
          '100%': { transform: 'rotate(45deg)' },
        }
      },
      animation: {
        bounce: 'bounce 3s ease-in-out infinite',
        rotateimg:'rotateimg 0.5s ease-in-out  forwards',
      },
      colors:{
        primary:{
          100:"#009fe3",
          200:""
        },
        success:{
          100:"#27C7D4",
          200:""
        },
        info:{
          100:"#B6D8F2",
          200:""
        },
        danger:{
          100:"#BD3100",
          200:""
        },
        secondary:{
          100:"#EBF2FA",
          200:""
        },
        warning:{
          100:"#FE895E",
          200:""
        },
        dark:{
          100:"#1E2745",
          200:"#182039",
          300:"#161D34"
        },
        login:{
          100:"#e5e8f0",
          100:"#f4f2fa",
        },
      }
    },
  },
  plugins: [],
}
