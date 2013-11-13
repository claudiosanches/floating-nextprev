"use strict";

module.exports = function(grunt) {

    // auto load grunt tasks
    require('load-grunt-tasks')(grunt);

    var pluginConfig = {

        // gets the package vars
        pkg: grunt.file.readJSON("package.json"),

        // plugin directories
        dirs: {
            admin_images: "admin/assets/images",
            public_js: "public/assets/js",
            public_css: "public/assets/css",
            public_sass: "public/assets/sass",
            public_images: "public/assets/images",
            public_fonts: "public/assets/fonts"
        },

        // svn settings
        svn_settings: {
            path: "../../../../wp_plugins/<%= pkg.name %>",
            tag: "<%= svn_settings.path %>/tags/<%= pkg.version %>",
            trunk: "<%= svn_settings.path %>/trunk",
            exclude: [
                ".editorconfig",
                ".git/",
                ".gitignore",
                ".jshintrc",
                ".sass-cache/",
                "node_modules/",
                "<%= dirs.public_sass %>/",
                "<%= dirs.public_js %>/<%= pkg.name %>.js",
                "Gruntfile.js",
                "README.md",
                "package.json",
                "*.zip"
            ]
        },

        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: ".jshintrc"
            },
            all: [
                "Gruntfile.js",
                "<%= dirs.public_js %>/<%= pkg.name %>.js"
            ]
        },

        // uglify to concat and minify
        uglify: {
            dist: {
                files: {
                    "<%= dirs.public_js %>/<%= pkg.name %>.min.js": ["<%= dirs.public_js %>/<%= pkg.name %>.js"]
                }
            }
        },

        // compass and scss
        compass: {
            dist: {
                options: {
                    httpPath: "",
                    sassDir: "<%= dirs.public_sass %>",
                    cssDir: "<%= dirs.public_css %>",
                    imagesDir: "<%= dirs.public_images %>",
                    javascriptsDir: "<%= dirs.public_js %>",
                    fontsDir: "<%= dirs.public_fonts %>",
                    environment: "production",
                    relativeAssets: true,
                    noLineComments: true,
                    outputStyle: "compressed"
                }
            }
        },

        // watch for changes and trigger compass, jshint and uglify
        watch: {
            compass: {
                files: [
                    "<%= compass.dist.options.sassDir %>/**"
                ],
                tasks: ["compass"]
            },
            js: {
                files: [
                    "<%= jshint.all %>"
                ],
                tasks: ["jshint", "uglify"]
            }
        },

        // image optimization
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 7,
                    progressive: true
                },
                files: [{
                    expand: true,
                    cwd: "./",
                    src: "screenshot-*.png",
                    dest: "./"
                }, {
                    expand: true,
                    cwd: "<%= dirs.admin_images %>/",
                    src: "*.png",
                    dest: "<%= dirs.admin_images %>/"
                }]
            }
        },

        // rsync commands used to take the files to svn repository
        rsync: {
            options: {
                args: ["--verbose"],
                exclude: "<%= svn_settings.exclude %>",
                recursive: true
            },
            tag: {
                options: {
                    src: "./",
                    dest: "<%= svn_settings.tag %>"
                }
            },
            trunk: {
                options: {
                src: "./",
                dest: "<%= svn_settings.trunk %>"
                }
            }
        },

        // shell command to commit the new version of the plugin
        shell: {
            svn_add: {
                command: 'svn add --force * --auto-props --parents --depth infinity -q',
                options: {
                    stdout: true,
                    stderr: true,
                    execOptions: {
                        cwd: "<%= svn_settings.path %>"
                    }
                }
            },
            svn_commit: {
                command: "svn commit -m 'updated the plugin version to <%= pkg.version %>'",
                options: {
                    stdout: true,
                    stderr: true,
                    execOptions: {
                        cwd: "<%= svn_settings.path %>"
                    }
                }
            }
        }
    };

    // initialize grunt config
    // --------------------------
    grunt.initConfig(pluginConfig);

    // register tasks
    // --------------------------

    // default task
    grunt.registerTask("default", [
        "jshint",
        "compass",
        "uglify"
    ]);

    // deploy task
    grunt.registerTask("deploy", [
        "default",
        "rsync:tag",
        "rsync:trunk",
        "shell:svn_add",
        "shell:svn_commit"
    ]);

};
