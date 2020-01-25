"use strict";

const browserSync = require("browser-sync").create();
var gulp = require("gulp");
var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var cleanCSS = require("gulp-clean-css");
var sourcemaps = require("gulp-sourcemaps");
const sass = require("gulp-sass");
// var merge = require("merge-stream");
// var rename = require("gulp-rename");
// var del = require('del');
// var zip = require('gulp-zip');

var root = "public/assets/";
var paths = {
  distRoot: root + "dist"
};
var jSBundle = [
  root + "js/vendor/MochiKit/MochiKit.js",
  root + "js/vendor/MochiKit/Base.js",
  root + "js/vendor/MochiKit/Iter.js",
  root + "js/vendor/MochiKit/Logging.js",
  root + "js/vendor/MochiKit/DateTime.js",
  root + "js/vendor/MochiKit/Format.js",
  root + "js/vendor/MochiKit/Async.js",
  root + "js/vendor/MochiKit/DOM.js",
  root + "js/vendor/MochiKit/Selector.js",
  root + "js/vendor/MochiKit/Style.js",
  root + "js/vendor/MochiKit/LoggingPane.js",
  root + "js/vendor/MochiKit/Color.js",
  root + "js/vendor/MochiKit/Signal.js",
  root + "js/vendor/MochiKit/Position.js",
  root + "js/vendor/MochiKit/Visual.js",
  root + "js/vendor/MochiKit/DragAndDrop.js",
  root + "js/vendor/MochiKit/Sortable.js",
  root + "js/interpreter.js"
];
var cssBundle = [
  root + "css/normalize.css",
  root + "css/skeleton.css",
  root + "css/interpreter.css",
  root + "css/custom.css",
  root + "src/css/scss/**/*.scss"
];

function js() {
  return (
    gulp
      .src(jSBundle)
      .pipe(concat("app.min.js"))
      // .pipe(uglify())
      .pipe(gulp.dest(paths.distRoot))
      .pipe(browserSync.stream())
  );
}

function css() {
  return gulp
    .src(cssBundle)
    .pipe(
      sass({
        compass: true,
        bundleExec: true,
        sourcemap: true,
        sourcemapPath: "../src/sass"
      }).on("error", sass.logError)
    )
    .pipe(concat("app.min.css"))
    .pipe(
      cleanCSS(
        {
          debug: true,
          compatibility: "ie8"
        },
        details => {
          console.log(`${details.name}: ${details.stats.originalSize}`);
          console.log(`${details.name}: ${details.stats.minifiedSize}`);
        }
      )
    )
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(paths.distRoot))
    .pipe(browserSync.stream());
}

function watch() {
  browserSync.init({
    browser: ["google-chrome", "firefox"],
    notify: true,
    // host: "192.168.1.1",
    port: 4242,
    https: false,
    httpModule: "http",
    server: {
      baseDir: ".",
      index: "index.html",
      serveStaticOptions: {
        extensions: ["html"]
      }
    }
  });
  gulp.watch("assets/css/**/*.css", css);
  gulp.watch("assets/css/**/*.scss", css);
  gulp.watch("assets/js/**/*.js", js);

  gulp.watch("./*.html").on("change", browserSync.reload);
  gulp.watch("./assets/css/**/*.css").on("change", browserSync.reload);
  gulp.watch("./assets/css/**/*.scss").on("change", browserSync.reload);
  gulp.watch("./assets/js/**/*.js").on("change", browserSync.reload);
}

exports.css = css;
exports.js = js;
exports.watch = watch;
