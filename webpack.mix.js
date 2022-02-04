const mix = require('laravel-mix');
const path = require('path')
require('laravel-mix-merge-manifest')

mix
  .mergeManifest()
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .options({
    processCssUrls: false,
    postCss: [require('autoprefixer'), require('tailwindcss')('./tailwind.config.js')],
  })
  .version()

if (!mix.inProduction()) {
  mix.sourceMaps()
}