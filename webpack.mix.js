require('laravel-mix-polyfill');
const config = require('./webpack.config');
const mix = require('laravel-mix');
const productionSourceMaps = false;

mix.webpackConfig(config);
mix
    .js('resources/js/app.js', 'public/dist/js')
    .extract(
        [
            // 'bootstrap',
            'bootstrap-material-design',
            // 'vue',
            'jquery'
        ]
    )
    .version()
    .options({
        processCssUrls: false,
    })
    .sass('resources/assets/scss/app.scss', 'public/dist/css', {
        sassOptions: {
            outputStyle: 'nested',
            processCssUrls: false
        },
        implementation: require('node-sass'),
    })
    .sourceMaps(productionSourceMaps, 'source-map')
    .polyfill({
        enabled: true,
        useBuiltIns: "usage",
        targets: {"firefox": "50", "ie": 11}
    })
;

if (mix.inProduction()) {
    mix.version()
        .webpackConfig({
            devtool: 'source-map',
        })
    ;
} else {
    mix.browserSync('http://laminas-adminpanel.local/');
    // Development settings
    mix.version()
        .webpackConfig({
            devtool: 'cheap-eval-source-map', // Fastest for development
        });
}
