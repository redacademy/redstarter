var gulp = require('gulp'),
    prettyError = require('gulp-prettyerror'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    rename = require('gulp-rename'),
    cssnano = require('gulp-cssnano'),
    uglify = require('gulp-uglify'),
    eslint = require('gulp-eslint'),
    browserSync = require('browser-sync');

gulp.task('sass', function () {
    gulp.src('./sass/style.scss')
        .pipe(prettyError())
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions']
        }))
        .pipe(gulp.dest('./'))
        .pipe(cssnano())
        .pipe(rename('style.min.css'))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./build/css'));
});

gulp.task('scripts', ['lint'], function () {
    gulp.src('./js/*.js')
        .pipe(uglify())
        .pipe(rename({
            extname: '.min.js'
        }))
        .pipe(gulp.dest('./build/js'))
});

gulp.task('lint', function () {
    return gulp.src(['./js/*.js'])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

gulp.task('browser-sync', function () {
    var files = [
        './build/css/*.css',
        './build/js/*.js',
        './*.php',
        './**/*.php',
    ];

    browserSync.init(files, {
        proxy: 'localhost[:port-here]/[your-dir-name-here]',
    });

    gulp.watch(files).on('change', browserSync.reload);
});

gulp.task('watch', function () {
    gulp.watch('./sass/*.scss', ['sass']);
    gulp.watch('./js/*.js', ['scripts']);
});

gulp.task('default', ['watch', 'browser-sync']);
