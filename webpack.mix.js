const mix = require('laravel-mix');
const path = require('path')
require('laravel-mix-merge-manifest')

mix
  .mergeManifest()
  .js('resources/js/app.js', 'public/js')
  .vue()
  .sass('resources/sass/app.scss', 'public/css')
  .options({
    processCssUrls: false,
    postCss: [require('autoprefixer'), require('tailwindcss')('./tailwind.config.js')],
  })
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      alias: {
        vue$: 'vue/dist/vue.js',
        '@': path.resolve('resources/js'),
      },
    },
  })
  .version()

if (!mix.inProduction()) {
  mix.sourceMaps()
}