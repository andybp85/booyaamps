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
        app  : require('./bower.json').appPath || 'app',
        idx  : 'app/htdocs',
        dist : 'dist',
        test : 'test',
        tmp  : '.tmp'
    },
    rewrite = require('connect-modrewrite-jgchristian');

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
                files: ['/bower_components/**/*.js'],
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
                    '{<%= yeoman.tmp %>,<%= yeoman.app %>}/src/scripts/**/*.js',
                    '{<%= yeoman.tmp %>,<%= yeoman.app %>}/src/styles/{,*/}*.css',
                    '<%= yeoman.app %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
                ]
            }
        },

        sass: {
            all: {
                options: {
                    sourceMap: true,
                    loadPath: [
                        'bower_components/bourbon/app/assets/stylesheets',
                        'bower_components/neat/app/assets/stylesheets'
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

        open: {
            app: {
                path: 'http://<%= connect.options.hostname %>:<%= connect.options.port %>/'
            },
            dist: {
                path: 'http://<%= connect.options.hostname %>:<%= connect.options.port %>/'
            },
            report: {
                path: 'docs/complexity/index.html'
            }
        },

        connect: {
            options: {
                port: 9000,
                // change this to '0.0.0.0' to access the server from outside
                hostname: 'booyaamps.com',
                //hostname: '127.0.0.1',
                debug: true,
            },
            livereload: {
                options: {
                    middleware: function (connect, options) {

                        var middleware = [
                            rewrite(['!\\.php|\\.html|\\.js|\\.css|\\.svg|\\.jp(e?)g|\\.png|\\.gif$ /index.php [QSA,L]']),
                            lrSnippet,
                            gateway(__dirname + path.sep + config.idx, {
                                '.php': 'php-cgi'
                            }),
                            mountFolder(connect, '<%= yeoman.tmp %>'),
                            connect().use('/bower_components', serveStatic('./bower_components')),
                            mountFolder(connect, config.idx)
                            ];

                        return middleware;
                    },

                   /*     var middleware = [];*/

                        /*var rules = [*/
                            //'!\\.html|\\.js|\\.css|\\.svg|\\.jp(e?)g|\\.png|\\.gif$ /index.php'
                        //];
                        /*middleware.push(rewrite(rules));*/

                        //middleware.push([
                            //lrSnippet,
                            //gateway(__dirname + path.sep + config.idx, {
                                //'.php': 'php-cgi'
                            //}),
                            //mountFolder(connect, '<%= yeoman.tmp %>'),
                            //connect().use('/bower_components', serveStatic('./bower_components')),
                            //mountFolder(connect, config.idx)
                        //]);

                        ////grunt.log.writeln(middleware);

                        //return middleware;
                    /*}*/
                }
            }
        },

        wiredep: {
            task: {
                src: [
                    '<%= yeoman.app %>/src/templates/base.tpl',
                    '<%= yeoman.app %>/src/templates/pages/*.tpl',
                    '<%= yeoman.app %>/src/sass/main.scss'
                ],
                exclude: [
                    'bower_components/codemirror/',
                    'bower_components/solarized'
                ]

                //options: {
                // See wiredep's configuration documentation for the options
                // you may pass:

                // https://github.com/taptapship/wiredep#configuration
                //}
            }
        },
        //wiredepAdmin: {
            //task: {
                //src: [
                    //'<%= yeoman.app %>/src/templates/admin-base.tpl',
                    //'<%= yeoman.app %>/src/templates/admin/*.tpl',
                    //'<%= yeoman.app %>/src/sass/admin.scss'
                //],
                //exclude: [
                    //'bower_components/jquery.transit/',
                //]

                ////options: {
                //// See wiredep's configuration documentation for the options
                //// you may pass:

                //// https://github.com/taptapship/wiredep#configuration
                ////}
            //}
        /*},*/
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
            return grunt.task.run(['build', 'open:dist', 'connect:dist:keepalive']);
        }

        grunt.task.run([
            'clean:server',
            //'wiredep',
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
