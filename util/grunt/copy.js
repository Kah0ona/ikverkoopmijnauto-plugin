// -------------------------------------
// Grunt copy
// -------------------------------------

module.exports = function (grunt) {

  return {
    // ----- Copy code files ----- //
    all: {
      files: [
	  
	  {
		cwd: 'dev/',
        src: '**',
        dest: 'dist/',
        expand: true
      }]
    }
  }
}; 
