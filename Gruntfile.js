/* jshint node:true */
var expandHomeDir = require( 'expand-home-dir' );

module.exports = function( grunt ) {
'use strict';

	grunt.initConfig({

		// Setting folder templates
		dirs: {
			css:    'assets/css',
			fonts:  'assets/fonts',
			images: 'assets/images',
			js:     'assets/js'
		},

		// gets the package vars
		pkg: grunt.file.readJSON( 'package.json' ),
		svn_settings: {
			path: expandHomeDir( '~/Projects/wordpress-plugins-svn/' ) + '<%= pkg.name %>',
			tag: '<%= svn_settings.path %>/tags/<%= pkg.version %>',
			trunk: '<%= svn_settings.path %>/trunk',
			exclude: [
				'.git/',
				'.tx/',
				'.editorconfig',
				'.gitignore',
				'.jshintrc',
				'node_modules/',
				'Gruntfile.js',
				'README.md',
				'package.json',
				'*.zip'
			]
		},

		// Javascript linting with jshint
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'Gruntfile.js',
				'<%= dirs.js %>/*/*/*.js',
				'!<%= dirs.js %>/*/*/*.min.js'
			]
		},

		// Minify .js files.
		uglify: {
			options: {
				preserveComments: /^!/
			},
			frontend: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/frontend/',
					src: [
						'*.js',
						'!*.min.js'
					],
					dest: '<%= dirs.js %>/frontend/',
					ext: '.min.js'
				}]
			}
		},

		sass: {
			options: {
				sourcemap: 'none',
				style: 'compressed'
			},
			frontend: {
				files: [{
					expand: true,
					cwd: '<%= dirs.css %>/frontend/',
					src: ['*.scss'],
					dest: '<%= dirs.css %>/frontend/',
					ext: '.css'
				}]
			}
		},

		// Watch changes for assets
		watch: {
			js: {
				files: [
					'<%= dirs.js %>/*/*.js',
					'!<%= dirs.js %>/*/*.min.js'
				],
				tasks: ['jshint', 'uglify']
			},
			sass: {
				files: [
					'<%= dirs.css %>/*/*.scss'
				],
				tasks: ['sass']
			}
		},

		// Create .pot file
		makepot: {
			dist: {
				options: {
					type: 'wp-plugin'
				}
			}
		},

		// Check text domain
		checktextdomain: {
			options:{
				text_domain: '<%= pkg.name %>',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src:  [
					'**/*.php', // Include all files
					'!node_modules/**' // Exclude node_modules/
				],
				expand: true
			}
		},

		// Rsync commands used to take the files to svn repository
		rsync: {
			options: {
				args: ['--verbose'],
				exclude: '<%= svn_settings.exclude %>',
				syncDest: true,
				recursive: true
			},
			tag: {
				options: {
					src: './',
					dest: '<%= svn_settings.tag %>'
				}
			},
			trunk: {
				options: {
				src: './',
				dest: '<%= svn_settings.trunk %>'
				}
			}
		},

		// Shell command to commit the new version of the plugin
		shell: {
			// Remove delete files.
			svn_remove: {
				command: 'svn st | grep \'^!\' | awk \'{print $2}\' | xargs svn --force delete',
				options: {
					stdout: true,
					stderr: true,
					execOptions: {
						cwd: '<%= svn_settings.path %>'
					}
				}
			},
			// Add new files.
			svn_add: {
				command: 'svn add --force * --auto-props --parents --depth infinity -q',
				options: {
					stdout: true,
					stderr: true,
					execOptions: {
						cwd: '<%= svn_settings.path %>'
					}
				}
			},
			// Commit the changes.
			svn_commit: {
				command: 'svn commit -m "updated the plugin version to <%= pkg.version %>"',
				options: {
					stdout: true,
					stderr: true,
					execOptions: {
						cwd: '<%= svn_settings.path %>'
					}
				}
			}
		},

		// Create README.md for GitHub.
		wp_readme_to_markdown: {
			options: {
				screenshot_url: 'http://ps.w.org/<%= pkg.name %>/assets/{screenshot}.png'
			},
			dest: {
				files: {
					'README.md': 'readme.txt'
				}
			}
		}

	});

	// Load tasks
	grunt.loadNpmTasks( 'grunt-checktextdomain' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-rsync' );
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );

	// Register tasks
	grunt.registerTask( 'default', [
		'jshint',
		'uglify',
		'sass'
	]);

	grunt.registerTask( 'readme', 'wp_readme_to_markdown' );

	grunt.registerTask( 'deploy', [
		'default',
		'rsync:tag',
		'rsync:trunk',
		'shell:svn_remove',
		'shell:svn_add',
		'shell:svn_commit'
	] );

};
