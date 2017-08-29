/**
 * This file contains build tasks that will create a ready-to-install zip archive
 * without any development resources or dependencies
 *
 * @version 1.0.0
 */

var gulp = require( 'gulp-help' )( require( 'gulp' ) ),
	del = require( 'del' ),
	fs = require( 'fs' ),
	plugins = require( 'gulp-load-plugins' )();





// -----------------------------------------------------------------------------
// Copy theme folder outside in a build folder, recreate styles before that
// -----------------------------------------------------------------------------

gulp.task( 'copy-folder', 'Copy theme production files to a build folder', function() {
	return gulp.src( './' )
	           .pipe( plugins.exec( 'rm -Rf ./../build; mkdir -p ./../build/' + config.theme + '; rsync -av --exclude="node_modules" ./* ./../build/' + config.theme + '/', {
		           silent: true,
		           continueOnError: true // default: false
	           } ) );
} );





// -----------------------------------------------------------------------------
// Replace the components' text domain with the theme's
// -----------------------------------------------------------------------------

gulp.task( 'txtdomain-replace', ['copy-folder'], function() {
	gulp.src( '../build/' + config.theme + '/components/**/*.php' )
	    .pipe( plugins.replace( /['|"]components['|"]/g, '\'' + config.theme_txtdomain + '\'' ) )
	    .pipe( gulp.dest( '../build/' + config.theme + '/components' ) );
} );





// -----------------------------------------------------------------------------
// Remove unneeded files and folders from the build folder
// -----------------------------------------------------------------------------

gulp.task( 'build', 'Remove unneeded files and folders from the build folder', ['txtdomain-replace'], function() {

	// files that should not be present in build
	files_to_remove = [
		'**/codekit-config.json',
		'node_modules',
		'config.rb',
		'gulp-tasks',
		'gulpfile.js',
		'gulpconfig.js',
		'package.json',
		'pxg.json',
		'build',
		'css',
		'.idea',
		'.editorconfig',
		'**/.svn*',
		'**/*.css.map',
		'**/.sass*',
		'.sass*',
		'**/.git*',
		'*.sublime-project',
		'.DS_Store',
		'**/.DS_Store',
		'__MACOSX',
		'**/__MACOSX',
		'README.md',
		'.csscomb',
		'.codeclimate.yml',
		'tests',
		'circle.yml'
	];

	files_to_remove.forEach( function( e, k ) {
		files_to_remove[k] = '../build/' + config.theme + '/' + e;
	} );

	return del.sync( files_to_remove, {force: true} );
} );





// -----------------------------------------------------------------------------
// Create the theme installer archive and delete the build folder
// -----------------------------------------------------------------------------

gulp.task( 'zip', 'Create the theme installer archive and delete the build folder', ['build'], function() {

	var versionString = '';

	// get theme version from styles.css
	var contents = fs.readFileSync( "./style.css", "utf8" );

	// split it by lines
	var lines = contents.split( /[\r\n]/ );

	function checkIfVersionLine( value, index, ar ) {
		var myRegEx = /^[Vv]ersion:/;
		return myRegEx.test( value );
	}

	// apply the filter
	var versionLine = lines.filter( checkIfVersionLine );

	versionString = versionLine[0].replace( /^[Vv]ersion:/, '' ).trim();
	versionString = '-' + versionString.replace( /\./g, '-' );

	return gulp.src( './' )
	           .pipe( plugins.exec( 'cd ./../; rm -rf ' + config.theme[0].toUpperCase() + config.theme.slice( 1 ) + '*.zip; cd ./build/; zip -r -X ./../' + config.theme[0].toUpperCase() + config.theme.slice( 1 ) + '-Installer' + versionString + '.zip ./; cd ./../; rm -rf build' ) );
} );
