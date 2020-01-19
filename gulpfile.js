"use strict";

var gulp = require('gulp');
var minifyCSS = require('gulp-csso');
var concat = require('gulp-concat');
var merge = require("merge-stream");
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var del = require('del');
var zip = require('gulp-zip');

var version = '1.0.0';
var root = 'assets/';
var buildRoot = root + 'dist/'+version;
var paths = {
	distRoot: buildRoot,
	vendoribrary: vendor,
};


var commonJSBundle = [
];
