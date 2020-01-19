"use strict";

var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-csso');
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
	root +'js/vendor/MochiKit/MochiKit.js',
	root +'js/vendor/MochiKit/Base.js',
	root +'js/vendor/MochiKit/Iter.js',
	root +'js/vendor/MochiKit/Logging.js',
	root +'js/vendor/MochiKit/DateTime.js',
	root +'js/vendor/MochiKit/Format.js',
	root +'js/vendor/MochiKit/Async.js',
	root +'js/vendor/MochiKit/DOM.js',
	root +'js/vendor/MochiKit/Selector.js',
	root +'js/vendor/MochiKit/Style.js',
	root +'js/vendor/MochiKit/LoggingPane.js',
	root +'js/vendor/MochiKit/Color.js',
	root +'js/vendor/MochiKit/Signal.js',
	root +'js/vendor/MochiKit/Position.js',
	root +'js/vendor/MochiKit/Visual.js',
	root +'js/vendor/MochiKit/DragAndDrop.js',
	root +'js/vendor/MochiKit/Sortable.js',
	root +'js/interpreter.js',
];

gulp.task('asset:minify:js', function () {
	return gulp.src(commonJSBundle)
		.pipe(concat('app.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(paths.distRoot));
});

//::CSS
var assetCssBundle = [
	root +'css/normalize.css',
	root +'css/skeleton.css',
	root +'css/interpreter.css',
]
gulp.task('asset:minify:css', function () {
	return gulp.src(assetCssBundle)
		.pipe(concat('app.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest(paths.distRoot));
});