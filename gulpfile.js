const gulp = require('gulp'),
    composer = require('gulp-uglify/composer'),
    concat = require('gulp-concat'),
    format = require('date-format'),
    header = require('gulp-header'),
    replace = require('gulp-replace'),
    uglifyjs = require('uglify-js'),
    uglify = composer(uglifyjs, console),
    pkg = require('./_build/config.json');

const banner = '/*!\n' +
    ' * <%= pkg.name %> - <%= pkg.description %>\n' +
    ' * Version: <%= pkg.version %>\n' +
    ' * Build date: ' + format("yyyy-MM-dd", new Date()) + '\n' +
    ' */';
const year = new Date().getFullYear();

gulp.task('scripts-fontawesome', function () {
    return gulp.src([
        'source/js/types/fontawesome/superboxfontawesome.panel.inputoptions.js'
    ])
        .pipe(concat('superboxfontawesome.panel.inputoptions.min.js'))
        .pipe(uglify())
        .pipe(header(banner + '\n', {pkg: pkg}))
        .pipe(gulp.dest('assets/components/superboxfontawesome/js/types/fontawesome/'))
});
gulp.task('scripts', gulp.series('scripts-fontawesome'));

gulp.task('bump-copyright', function () {
    return gulp.src([
        'core/components/superboxfontawesome/model/superboxfontawesome/superboxfontawesome.class.php',
        'core/components/superboxfontawesome/src/SuperBoxFontawesome.php'
    ], {base: './'})
        .pipe(replace(/Copyright 2016(-\d{4})? by/g, 'Copyright ' + (year > 2016 ? '2016-' : '') + year + ' by'))
        .pipe(gulp.dest('.'));
});
gulp.task('bump-version', function () {
    return gulp.src([
        'core/components/superboxfontawesome/src/SuperBoxFontawesome.php'
    ], {base: './'})
        .pipe(replace(/version = '\d+\.\d+\.\d+[-a-z0-9]*'/ig, 'version = \'' + pkg.version + '\''))
        .pipe(gulp.dest('.'));
});
gulp.task('bump-docs', function () {
    return gulp.src([
        'mkdocs.yml',
    ], {base: './'})
        .pipe(replace(/&copy; 2016(-\d{4})?/g, '&copy; ' + (year > 2016 ? '2016-' : '') + year))
        .pipe(gulp.dest('.'));
});
gulp.task('bump', gulp.series('bump-copyright', 'bump-version', 'bump-docs'));


gulp.task('watch', function () {
    // Watch .js files
    gulp.watch(['source/js/**/*.js'], gulp.series('scripts-mgr'));
    // Watch .scss files
    gulp.watch(['source/sass/**/*.scss'], gulp.series('sass-mgr'));
});

// Default Task
gulp.task('default', gulp.series('bump', 'scripts-fontawesome'));