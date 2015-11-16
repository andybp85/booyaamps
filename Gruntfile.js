'use strict';

var LIVERELOAD_PORT = 35729,
    lrSnippet = require('connect-livereload')({port: LIVERELOAD_PORT});


module.exports = function(grunt) {
    require('time-grunt')(grunt);
    require('load-grunt-tasks')(grunt);

    var config = {
        app  : require('./bower.json').appPath || 'app',
        idx  : 'app/htdocs',
        dist : 'dist',
        test : 'test',
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
                './<%= yeoman.app %>/src/**/*.php',
                './<%= yeoman.idx %>/**/*.php',
                './<%= yeoman.test %>/**/*.php'
            ]
        },
        phpcs: {
            options: {
                bin: './vendor/bin/phpcs',
                standard: './phpcs.xml.dist'
            },
            application: {
                dir: [
                    './<%= yeoman.app %>/src',
                    './<%= yeoman.idx %>',
                    './<%= yeoman.test %>'
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
                dir: [
                    './<%= yeoman.app %>/src',
                    './<%= yeoman.idx %>'
                ]
            }
        },
        phpcpd: {
            options: {
                bin: './vendor/bin/phpcpd',
                quiet: false,
                ignoreExitCode: true
            },
            application: {
                dir: [
                    './<%= yeoman.app %>/src',
                    './<%= yeoman.idx %>'
                ]
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
                        '<%= yeoman.tmp %>',
                        '<%= yeoman.dist %>/*'
                    ]
                }]
            },
            server: '<%= yeoman.tmp %>'
        },

        watch: {
            javascript: {
                files: ['<%= yeoman.app %>/scripts/{,*/}*.js'],
                tasks: ['jshint']
            },
            sass: {
                files: ['<%= yeoman.app %>/src/sass/{,*/}*.{scss,sass}'],
                tasks: ['sass']
            },
            bower: {
                files: ['/app/htdocs/bower_components/**/*.js'],
                tasks: ['bower']
            },
            livereload: {
                options: {
                    livereload: LIVERELOAD_PORT
                },
                files: [
                    '<%= yeoman.app %>/{,*/}*.html',
                    '<%= yeoman.app %>/{,*/}*.php',
                    '<%= yeoman.app %>/src/templates/{,*/}*.tpl',
                    '{<%= yeoman.tmp %>,<%= yeoman.app %>}/htdocs/scripts/**/*.js',
                    '{<%= yeoman.tmp %>,<%= yeoman.app %>}/htdocs/styles/*.css',
                    '<%= yeoman.app %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
                ]
            }
        },

        sass: {
            all: {
                options: {
                    sourceMap: true,
                    loadPath: [
                        'app/htdocs/bower_components/bourbon/app/assets/stylesheets',
                        'app/htdocs/bower_components/neat/app/assets/stylesheets'
                    ]
                },
                files: {
                    '<%= yeoman.idx %>/styles/main.css': '<%= yeoman.app %>/src/sass/main.scss',
                    '<%= yeoman.idx %>/styles/admin.css': '<%= yeoman.app %>/src/sass/admin.scss'
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

        /*open: {*/
            //app: {
                //path: 'http://<%= php.options.hostname %>:<%= php.options.port %>/'
            //},
            //dist: {
                //path: 'http://<%= php.options.hostname %>:<%= php.options.port %>/'
            //},
            //report: {
                //path: 'docs/complexity/index.html'
            //}
        /*},*/

        php: {
            options: {
                port: 9000,
                // change this to '0.0.0.0' to access the server from outside
                hostname: 'booyaamps.com',
                //hostname: '127.0.0.1',
                debug: false,
                keepalive: false,
                base: config.idx,
                open: true
            },
            livereload: {
                options: {
                    middleware: function (php, options) {

                        var middleware = [
                            lrSnippet
                            ];

                        return middleware;
                    },

                }
            },
        },

        wiredep: {
            task: {
                src: [
                    '<%= yeoman.app %>/src/templates/base.tpl',
                    '<%= yeoman.app %>/src/templates/pages/*.tpl',
                    '<%= yeoman.app %>/src/sass/main.scss'
                ],
                exclude: [
                    'app/htdocs/bower_components/codemirror/',
                    'app/htdocs/bower_components/solarized'
                ]

                //options: {
                // See wiredep's configuration documentation for the options
                // you may pass:

                // https://github.com/taptapship/wiredep#configuration
                //}
            }
        },

        bowerRequirejs: {
            all: {
                rjsConfig: '<%= yeoman.idx %>/scripts/common.js',
                options: {
                    'exclude-dev': true,
                    exclude: ['modernizr']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bower-requirejs');

    grunt.loadNpmTasks('grunt-wiredep');

    grunt.registerTask('serve', function(target) {
        if (target === 'dist') {
            return grunt.task.run(['build', 'open:dist', 'php:dist:keepalive']);
        }

        grunt.task.run([
            'clean:server',
            //'wiredep',
            'concurrent:server',
            'php:livereload',
            //'open:app',
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
