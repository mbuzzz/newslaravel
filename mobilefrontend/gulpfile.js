// Name: Newsten - Blog & Magazine Mobile HTML Template
// Author: Designing World
// Author URL: https://themeforest.net/user/designing-world

'use strict';

const {
    src,
    dest,
    parallel,
    series,
    watch
} = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('autoprefixer');
const postcss = require('gulp-postcss');

// Paths
const paths = {
    js: [
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        'node_modules/owl.carousel2/dist/owl.carousel.min.js',
        'node_modules/wowjs/dist/wow.min.js',
        'node_modules/jarallax/dist/jarallax.min.js',
        'node_modules/jarallax/dist/jarallax-video.min.js',
        'node_modules/jquery-waypoints/waypoints.min.js',
        'node_modules/counterup/jquery.counterup.min.js',
        'node_modules/jquery.easing/jquery.easing.min.js',
        'node_modules/jquery-animated-headlines/dist/js/jquery.animatedheadline.min.js'
    ],
    css: [
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/wowjs/css/libs/animate.css',
        'node_modules/jquery-animated-headlines/dist/css/jquery.animatedheadline.css',
        'node_modules/@tabler/icons-webfont/dist/tabler-icons.min.css',
        'node_modules/owl.carousel2/dist/assets/owl.carousel.min.css'
    ],
    fonts: 'node_modules/@tabler/icons-webfont/dist/fonts/*',
    staticAssets: 'static/*.*',
    staticImages: 'static/img/**/*',
    staticJS: 'static/js/*',
    staticCSS: 'static/css/*',
    scss: 'src/scss/*.scss',
    html: 'src/html/*.html'
};

// Move JS Files to dist/js
function js() {
    return src(paths.js)
        .pipe(dest('dist/js'));
}

// Move CSS Files to dist/css
function css() {
    return src(paths.css)
        .pipe(dest('dist/css'));
}

// Move Fonts to dist/css/fonts
function fonts() {
    return src(paths.fonts)
        .pipe(dest('dist/css/fonts'));
}

// Move Static Assets to dist/*
function staticAssets() {
    return src(paths.staticAssets)
        .pipe(dest('dist/'));
}

// Move Static Images to dist/img
function staticImages() {
    return src(paths.staticImages)
        .pipe(dest('dist/img'));
}

// Move Static JS Files to dist/js
function staticJS() {
    return src(paths.staticJS)
        .pipe(dest('dist/js'));
}

// Move Static CSS Files to dist/css
function staticCSS() {
    return src(paths.staticCSS)
        .pipe(dest('dist/css'));
}

// Convert SCSS to CSS and Move to dist/
function sassToCSS() {
    return src(paths.scss)
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(postcss([autoprefixer({ overrideBrowserslist: ['last 2 versions'] })]))
        .pipe(dest('dist/'));
}

// Move HTML Files to dist/
function htmlFiles() {
    return src(paths.html)
        .pipe(dest('dist/'));
}

// Watch for changes
function watching() {
    watch(paths.staticAssets, staticAssets);
    watch(paths.staticImages, staticImages);
    watch(paths.staticJS, staticJS);
    watch(paths.staticCSS, staticCSS);
    watch(paths.scss, sassToCSS);
    watch(paths.html, htmlFiles);
}

// Default task
exports.default = series(js, css, fonts, staticAssets, staticImages, staticJS, staticCSS, sassToCSS, htmlFiles, watching);

// Watch task
exports.watch = parallel(watching);