module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            build: {
                options: {
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: 'style.css.map',
                    sourceMapFilename: 'css/style.css.map'
                },
                files: {
                    'css/style.css': 'less/style.less'
                }
            }
        },
        watch: {
            less: {
                files: 'less/*.less',
                tasks: ['less']
            },
            livereload: {
                options: { livereload: true },
                files: ['js/**/*.js','css/**/*']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
};