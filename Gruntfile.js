'use strict';

var LIVERELOAD_PORT = 35729,
    lrSnippet = require('connect-livereload')({port: LIVERELOAD_PORT}),
    gateway = require('gateway'),
    serveStatic = require('serve-static'),
    path = require('path'),
    mountFolder = function (connect, dir) {
        return serveStatic(require('path').resolve(dir));
    };

module.exports = function(grunt) {
    require('time-grunt')(grunt);
    require('load-grunt-tasks')(grunt);

    var config = {
        app  : require('./bower.json').appPath || 'src',
        dist : 'dist',
        tmp  : '.tmp'
    };

    grunt.initConfig({

        yeoman : config,

        pkg: grunt.file.readJSON('./package.json'),
        composer: grunt.file.readJSON('./composer.json'),

        phplint: {
            options: {
                swapPath: '/tmp'
            },
            application: [
                './src/**/*.php',
                './test/**/*.php'
            ]
        },
        phpcs: {
            options: {
                bin: './vendor/bin/phpcs',
                standard: './phpcs.xml.dist'
            },
            application: {
                dir: [
                    './src',
                    './test'
                ]
            }
        },
        phpmd: {
            options: {
                bin: './vendor/bin/phpmd',
                rulesets: './phpmd.xml.dist',
                reportFormat: 'text'
            },
            application: {
                dir: './src'
            }
        },
        phpcpd: {
            options: {
                bin: './vendor/bin/phpcpd',
                quiet: false,
                ignoreExitCode: true
            },
            application: {
                dir: './src'
            }
        },
        phpunit: {
            options: {
                bin: './vendor/bin/phpunit',
                coverage: true
            },
            application: {
                configuration: './phpunit.xml.dist'
            }
        },


        clean: {
            dist: {
                files: [{
                    dot: true,
                    src: [
                        '.tmp',
                        '<%= yeoman.dist %>/*'
                    ]
                }]
            },
            server: '.tmp'
        },

        watch: {
            javascript: {
                files: ['<%= yeoman.app %>/scripts/**/*.js'],
                tasks: ['jshint']
            },
            sass: {
                files: ['<%= yeoman.app %>/styles/{,*/}*.{scss,sass}'],
                tasks: ['sass']
            },
            bower: {
                files: ['<%= yeoman.app %>/bower_components/**/*.js'],
                tasks: ['bower']
            },
            livereload: {
                options: {
                    livereload: LIVERELOAD_PORT
                },
                files: [
                    '<%= yeoman.app %>/{,*/}*.html',
                    '<%= yeoman.app %>/{,*/}*.php',
                    '{.tmp,<%= yeoman.app %>}/scripts/{,*/}*.js',
                    '{.tmp,<%= yeoman.app %>}/styles/{,*/}*.css',
                    '<%= yeoman.app %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
                ]
            }
        },

        sass: {
            all: {
                options: {
                    sourceMap: true
                },
                files: {
                    '.tmp/styles/main.css': '<%= yeoman.app %>/styles/main.scss'
                }
            }
        },

        concurrent: {
            server: [
                'sass'
            ],
            test: [
                'sass'
            ],
            dist: [
                'sass',
                'imagemin',
                'svgmin'
            ]
        },

        open: {
            app: {
                path: 'http://<%= connect.options.hostname %>:<%= connect.options.port %>/index.php'
            },
            dist: {
                path: 'http://<%= connect.options.hostname %>:<%= connect.options.port %>/index.php'
            },
            report: {
                path: 'docs/complexity/index.html'
            }
        },  

        connect: {
            options: {
                port: 9000,
                // change this to '0.0.0.0' to access the server from outside
                hostname: '127.0.0.1'
            },
            livereload: {
                options: {
                    middleware: function (connect) {
                        return [
                            lrSnippet,
                            gateway(__dirname + path.sep + config.app, {
                                '.php': 'php-cgi'
                            }),
                            mountFolder(connect, '.tmp'),
                            mountFolder(connect, config.app)
                        ];
                    }
                }
            }
        }
    });


    grunt.registerTask('serve', function(target) {
        if (target === 'dist') {
            return grunt.task.run(['build', 'open:dist', 'connect:dist:keepalive']);
        }

        grunt.task.run([
            'clean:server',
            'concurrent:server',
            'connect:livereload',
            'open:app',
            'watch'
        ]);
    });

    grunt.task.registerTask('build', 'Building', function() {
        grunt.log.writeln('Task ready to be implemented');
    });

    grunt.registerTask('check', ['phplint', 'phpcs', 'phpmd', 'phpcpd']);
    grunt.registerTask('test', ['phpunit']);

    grunt.registerTask('default', ['check', 'test']);
};
