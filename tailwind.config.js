module.exports = {
  content: [
    './resources/js/**/*.{html,js,vue}',
    './resources/views/**/*.blade.php',
    './packages/takeoffdesigngroup/cms/resources/views/**/*.blade.php',
  ],
  safelist: [
    'tracking-tight',
    'sm:grid-cols-2',
    'md:w-1/2',
    'md:mx-auto',
    'bg-no-repeat',
    'bg-cover',
    'min-h-[300px]',
    'min-h-[430px]',
    {
      pattern: /font-(light|normal|semibold|bold|extrabold)/,
    },
    {
      pattern: /flex-(20|25|33|40|50|60|66|75|80|100)/,
      variants: ['sm', 'lg', 'xl'],
    },
    {
      pattern: /text-(xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl)/,
      variants: ['sm', 'md', 'lg', 'xl'],
    },
    {
      pattern: /max-w-(xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl)/,
      variants: ['sm', 'lg', 'xl'],
    },
    {
      pattern: /m(t|r|b|l|y)-(4|6|10|12|14|20|32)/,
      variants: ['sm', 'lg', 'xl'],
    },
    {
      pattern: /m-(4|6|10|12|14|20|32)/,
      variants: ['sm', 'lg', 'xl'],
    },
    {
      pattern: /p(t|r|b|l|y)-(4|6|10|12|14|20|24|32)/,
      variants: ['sm', 'md', 'lg', 'xl'],
    },
    {
      pattern: /p-(4|6|10|12|14|20|24|32)/,
      variants: ['sm', 'md', 'lg', 'xl'],
    },
  ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--primary-color)',
        secondary: 'var(--secondary-color)',
        link: 'var(--link-color)',
        'link-hover': 'var(--link-hover-color)',
        'link-active': 'var(--link-active-color)',
      },
      flex: {
        20: '0 1 calc(20% - (1.5rem * 2))',
        25: '1 1 calc(25% - (1.5rem * 2))',
        33: '1 1 calc(33% - (1.5rem * 2))',
        40: '1 1 calc(40% - (1.5rem * 2))',
        50: '1 1 calc(50% - (1.5rem * 2))',
        60: '1 1 calc(60% - (1.5rem * 2))',
        66: '1 1 calc(66% - (1.5rem * 2))',
        75: '1 1 calc(75% - (1.5rem * 2))',
        80: '1 1 calc(80% - (1.5rem * 2))',
        100: '1 1 100%',
      },
    },
  },
  plugins: [require('@tailwindcss/aspect-ratio')],
}
