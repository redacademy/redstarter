var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    notify = require('gulp-notify'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    rename = require('gulp-rename'),
    minifyCSS = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    jscs = require('gulp-jscs'),
    jshint = require('gulp-jshint');

var plumberErrorHandler = {
   errorHandler: notify.onError({
      title: 'Gulp',
      message: 'Error: <%= error.message %>'
   })
};

gulp.task('sass', function() {
   gulp.src('./sass/style.scss')
      .pipe(plumber(plumberErrorHandler))
      .pipe(sass())
      .pipe(autoprefixer({
         browsers: ['last 2 versions']
      }))
      .pipe(gulp.dest('./'))
      .pipe(minifyCSS())
      .pipe(rename('style.min.css'))
      .pipe(gulp.dest('./build/css'));
});

gulp.task('scripts', function(){
    gulp.src('./js/*.js')
      .pipe(uglify())
      .pipe(rename({
        extname: '.min.js'
      }))
      .pipe(gulp.dest('./build/js'))
});

gulp.task('jscs', function () {
   return gulp.src('./js/*.js')
       .pipe(jscs('.jscsrc'));
});

gulp.task('lint', function() {
  return gulp.src('./js/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
});

gulp.task('watch', function() {
   gulp.watch('sass/*.scss', ['sass']);
   gulp.watch('js/*.js', ['scripts']);
});

gulp.task('default', ['sass', 'scripts']);
