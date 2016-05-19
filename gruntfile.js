'use strict';
module.exports = function(grunt) {

	require('time-grunt')(grunt);

	grunt.initConfig({

		// let us know if our JS is sound
		jshint: {
			options: {
				"bitwise": true,
				"browser": true,
				"curly": true,
				"eqeqeq": true,
				"eqnull": true,
				"es5": true,
				"esnext": true,
				"immed": true,
				"jquery": true,
				"latedef": false,
				"newcap": true,
				"noarg": true,
				"node": true,
				"strict": false,
				"trailing": true,
				"undef": true,
				"globals": {
					"jQuery": true,
					"_": true,
					"alert": true
				}
			},
			all: [
				'Gruntfile.js',
				'src/js/*.js' /*,
				'src/js/vendor/*.js'*/
			]
		},

		// concat and minify (no concat in below config as will be handled by W3TC).
		uglify: {
			dist: {
				files: {
					'build/js/plugins.min.js': [
						'src/js/vendor/plugins.js'
					],
					'build/js/admin-deferred.min.js': [
						'src/js/admin-deferred.js'
					],
					'build/js/onload.min.js': [
						'src/js/onload.js'
					],
					'build/js/deferred.min.js': [
						'src/js/deferred.js'
					]
				}
			}
		},

		// SASS
		sass: {
			options: {
				sourceMap: true
			},
			dist: {
				files: {
					'build/css/style.css': 'src/css/main.scss'
				}
			}
		},

		//ftp upload
		ftpush: {
			js: {
				simple: true,
				auth: {
					host: 'melbournecitycaravans.com.au',
					port: 21,
					authKey: 'mcc'
				},
				src: 'build/js',
				dest: '/public_html/wp-content/themes/scwd-custom/js'
			},
			css: {
				simple: true,
				auth: {
					host: 'melbournecitycaravans.com.au',
					port: 21,
					authKey: 'mcc'
				},
				src: 'build/css',
				dest: '/public_html/wp-content/themes/scwd-custom/'
			},
			php: {
				simple: true,
				auth: {
					host: 'melbournecitycaravans.com.au',
					port: 21,
					authKey: 'mcc'
				},
				src: 'src/templates/',
				dest: '/public_html/wp-content/themes/scwd-custom/'
			},
			images: {
				simple: true,
				auth: {
					host: 'melbournecitycaravans.com.au',
					port: 21,
					authKey: 'mcc'
				},
				src: 'src/images/',
				dest: '/public_html/wp-content/themes/scwd-custom/images/'
			}
		},

		// watch our project for changes
		watch: {
			sass: {
				files: [
					'src/css/*',
					'src/css/**/*',
					'src/css/vendor/*',
					'src/css/vendor/**/*'
				],
				tasks: ['sass']
			},
			js: {
				files: [
					'<%= jshint.all %>'
				],
				tasks: ['jshint', 'uglify']
			},
			ftpjs: {
				files: [
					'build/js/*'
				],
				tasks: ['ftpush:js']
			},
			ftpcss: {
				files: [
					'build/css/style.css'
				],
				tasks: ['ftpush:css']
			},
			ftpphp: {
				files: [
					'src/templates/*.php'
				],
				tasks: ['ftpush:php']
			},
			ftpimages: {
				files: [
					'src/images/*.*'
				],
				tasks: ['ftpush:images']
			}
		}

	});

	// load tasks
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-ftpush');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// register task
	grunt.registerTask('default', [
		'jshint',
		'sass',
		'uglify',
		'ftpush',
		'watch'
	]);

};