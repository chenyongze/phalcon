module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            wscn: {
                options: {
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: 'public/static/wscn/css/style.css.map',
                    sourceMapFilename: 'public/static/wscn/css/style.css.map'
                },
                files: {
                    'public/static/wscn/css/style.css': 'public/static/wscn/less/style.less'
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
                files: ['public/static/wscn/js/**/*.js','public/static/wscn/css/**/*']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
};