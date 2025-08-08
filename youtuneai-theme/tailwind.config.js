/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./assets/js/**/*.js",
    "./blocks/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#9d00ff',
        secondary: '#00e5ff',
        accent: '#00ff88',
        platinum: '#e5e4e2',
        'diamond-silver': '#c0c0c0',
        gunmetal: '#2a3439',
        'marble-white': '#f8f8ff',
        'marble-black': '#0f0f0f',
        velvet: '#722f37',
        'background-dark': '#050510',
        'background-card': 'rgba(20, 20, 40, 0.95)',
      },
      fontFamily: {
        'orbitron': ['Orbitron', 'sans-serif'],
        'raleway': ['Raleway', 'sans-serif'],
        'martian': ['Martian Mono', 'monospace'],
      },
      animation: {
        'float': 'float 3s ease-in-out infinite',
        'glow': 'glow 2s ease-in-out infinite alternate',
        'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        glow: {
          '0%': { textShadow: '0 0 5px currentColor' },
          '100%': { textShadow: '0 0 20px currentColor, 0 0 30px currentColor' },
        },
        'pulse-glow': {
          '0%, 100%': { 
            boxShadow: '0 0 5px rgba(157, 0, 255, 0.5)' 
          },
          '50%': { 
            boxShadow: '0 0 20px rgba(157, 0, 255, 0.8), 0 0 30px rgba(157, 0, 255, 0.6)' 
          },
        }
      },
    },
  },
  plugins: [],
}