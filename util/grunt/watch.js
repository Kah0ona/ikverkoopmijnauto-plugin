// -------------------------------------
// Grunt watch
// -------------------------------------

module.exports = {

  // ----- Watch tasks ----- //
  all: {
    files: [ 'dev/**' ],
    tasks: [
	  'clean:dist',
      'copy:all',
	  'rsync'
    ]
  },
};
