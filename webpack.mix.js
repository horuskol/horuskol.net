let build = require('./tasks/build.js');
let mix = require('laravel-mix');
let exec = require('child_process').exec;
let path = require('path');

const tailwindcss = require('tailwindcss');
const tailwindcfg = require('./tailwind.config');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets');
mix.webpackConfig({
    plugins: [
        build.jigsaw,
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
    .version()
    .browserSync({ proxy: 'localhost:8000'});