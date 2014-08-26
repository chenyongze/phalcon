module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            wscn: {
                options: {
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: 'style.css.map',
                    sourceMapFilename: 'style.css.map'
                },
                files: {
                    'public/wscn/css/style.css': 'public/wscn/less/style.less'
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
                files: ['public/wscn/js/**/*.js','public/wscn/css/**/*']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
};