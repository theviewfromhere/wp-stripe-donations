var config 				= require('./gulp.config')();
var gulp				= require('gulp'),
    util                = require('gulp-util'),
    sass            	= require('gulp-sass'),
    autoprefixer        = require('gulp-autoprefixer'),
    minifycss           = require('gulp-minify-css'),
    jshint 			    = require('gulp-jshint'),
    rename			    = require('gulp-rename'),
    plumber			    = require('gulp-plumber'),
    notify			    = require('gulp-notify'),
    uglify 			    = require('uglify-es'),
    removeLogging       = require("gulp-remove-logging"),
    composer 		    = require('gulp-uglify/composer'),
    pump 			    = require('pump'),
	clean				= require('gulp-clean');

var minify      = composer(uglify, console);
var log         = util.log;
var colors      = util.colors;

var plumberErrorHandler = { 
	errorHandler: notify.onError({
		title: 'Gulp',
		message: 'Error: <%= error.message%>'
	})
};

// bourbon > prefix > minify > rename > distribute
gulp.task('sass', function(cb) {
    pump([
        gulp.src(config.sourceFiles.scss),
        sass({
			includePaths: require('node-bourbon').includePaths
		}),
		autoprefixer('last 2 version', 'safari 5', 'ie 7', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'),
		minifycss(),
		rename({ suffix:'.min' }),
		gulp.dest(config.destination + './')
    ], cb);
});

// es v6 > jshint > minify > rename > distribute
gulp.task('js', function (cb) {
    pump([
        gulp.src(config.sourceFiles.js),
        plumber(plumberErrorHandler),
        // removeLogging({ namespace: ['console', 'window.console'] }),
        jshint({esversion: 6}),
        jshint.reporter('default', { verbose: true }),
        minify(),
        rename({ suffix:'.min' }),
        gulp.dest(config.destination + './')
    ], cb);
});

gulp.task('moveFiles', function(cb) {
	pump([
		gulp.src('./src/**/*.php'),
		gulp.dest(config.destination)
	], cb)
}) ;

gulp.task('clean', function () {
	return gulp.src(config.destination, {read: false})
		.pipe(clean());
});

// watch & compile all files > build
gulp.task('watch', function() {
	gulp.watch('src/**/*', ['build']);
});

// build > js/css/html/images
gulp.task('build', ['js', 'sass', 'moveFiles'], function() { });

gulp.task('default', ['build']);