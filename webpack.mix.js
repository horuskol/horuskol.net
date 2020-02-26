let build = require('./tasks/build.js');
let mix = require('laravel-mix');
let exec = require('child_process').exec;
let path = require('path');
require('laravel-mix-purgecss');

const tailwindcss = require('tailwindcss');
const tailwindcfg = require('./tailwind.config');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets');
mix.webpackConfig({
    plugins: [
        build.jigsaw,
        build.browserSync(),
        build.watch(['source/**/*.md', 'source/**/*.php', 'source/**/*.scss', '!source/**/_tmp/*']),
    ]
});

mix.options({
    processCssUrls: false,
    postCss: [ tailwindcss(tailwindcfg) ]
})
    .postCss('source/_assets/css/main.css', 'css/main.css')
    .js('source/_assets/js/main.js', 'js')
    .js('source/_assets/js/presentation.js', 'js')
    .purgeCss({
        folders: ['source']
    })
    .version();