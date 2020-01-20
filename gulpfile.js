"use strict";

const browserSync = require('browser-sync').create();
var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-csso');
const sass = require('gulp-sass');
// var merge = require("merge-stream");
// var rename = require("gulp-rename");
// var del = require('del');
// var zip = require('gulp-zip');


var version = '1.0.0';
var root = 'assets/';
var buildRoot = root + 'dist/';
var paths = {
    distRoot: buildRoot,
};


var commonJSBundle = [
    root + 'js/vendor/MochiKit/MochiKit.js',
    root + 'js/vendor/MochiKit/Base.js',
    root + 'js/vendor/MochiKit/Iter.js',
    root + 'js/vendor/MochiKit/Logging.js',
    root + 'js/vendor/MochiKit/DateTime.js',
    root + 'js/vendor/MochiKit/Format.js',
    root + 'js/vendor/MochiKit/Async.js',
    root + 'js/vendor/MochiKit/DOM.js',
    root + 'js/vendor/MochiKit/Selector.js',
    root + 'js/vendor/MochiKit/Style.js',
    root + 'js/vendor/MochiKit/LoggingPane.js',
    root + 'js/vendor/MochiKit/Color.js',
    root + 'js/vendor/MochiKit/Signal.js',
    root + 'js/vendor/MochiKit/Position.js',
    root + 'js/vendor/MochiKit/Visual.js',
    root + 'js/vendor/MochiKit/DragAndDrop.js',
    root + 'js/vendor/MochiKit/Sortable.js',
    root + 'js/interpreter.js',
];

function js() {
    return gulp.src(commonJSBundle)
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.distRoot))
        .pipe(browserSync.stream())
        ;
}

//::CSS
var assetCssBundle = [
    root + 'css/normalize.css',
    root + 'css/skeleton.css',
    root + 'css/interpreter.css',
    root + 'css/custom.css',
    root + 'src/css/scss/**/*.scss'
];

//compile scss into css
function style() {
    return gulp.src(assetCssBundle)
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('app.min.css'))
        .pipe(minifyCSS())
        .pipe(gulp.dest(paths.distRoot))
        .pipe(browserSync.stream())
        ;
}

function watch() {
    browserSync.init({
        server: {
            baseDir: ".",
            index: "index.html"
        }
    });
    gulp.watch('assets/css/**/*.css', style);
    gulp.watch('assets/css/**/*.scss', style);
    gulp.watch('assets/js/**/*.js', js);

    gulp.watch('./*.html').on('change', browserSync.reload);
    gulp.watch('./assets/css/**/*.css').on('change', browserSync.reload);
    gulp.watch('./assets/css/**/*.scss').on('change', browserSync.reload);
    gulp.watch('./assets/js/**/*.js').on('change', browserSync.reload);
}

exports.style = style;
exports.js = js;
exports.watch = watch;