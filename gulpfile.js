const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync');
const cssnano = require('gulp-cssnano');
const eslint = require('gulp-eslint');
const gulp = require('gulp');
const prettyError = require('gulp-prettyerror');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const terser = require('gulp-terser');
const babel = require('gulp-babel');

// Create basic Gulp tasks

gulp.task('sass', function() {
  return gulp
    .src('./sass/style.scss', { sourcemaps: true })
    .pipe(sourcemaps.init())
    .pipe(prettyError())
    .pipe(sass())
    .pipe(
      autoprefixer({
        browsers: ['last 2 versions']
      })
    )
    .pipe(gulp.dest('./'))
    .pipe(cssnano())
    .pipe(rename('style.min.css'))
    .pipe(sourcemaps.write('../maps'))
    .pipe(gulp.dest('./build/css'));
});

gulp.task('lint', function() {
  return gulp
    .src(['./js/*.js'])
    .pipe(prettyError())
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError());
});

gulp.task(
  'scripts',
  gulp.series('lint', function() {
    return gulp
      .src('./js/*.js')
      .pipe(
        babel({
          presets: ['env']
        })
      )
      .pipe(terser())
      .pipe(
        rename({
          extname: '.min.js'
        })
      )
      .pipe(gulp.dest('./build/js'));
  })
);

// Set-up BrowserSync and watch

gulp.task('browser-sync', function() {
  const files = [
    './build/css/*.css',
    './build/js/*.js',
    './*.php',
    './**/*.php'
  ];

  browserSync.init(files, {
    proxy: 'localhost[:port-here]/[your-dir-name-here]'
  });

  gulp.watch(files).on('change', browserSync.reload);
});

gulp.task('watch', function() {
  gulp.watch('js/*.js', gulp.series('scripts'));
  gulp.watch('sass/*.scss', gulp.series('sass'));
});

gulp.task('default', gulp.parallel('browser-sync', 'watch'));
