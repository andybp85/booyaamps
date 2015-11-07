"use strict";

require.config({
    paths: {
        "blueimp-file-upload" : "../bower_components/blueimp-file-upload/js/jquery.fileupload",
        'jquery'              : "../bower_components/jquery/dist/jquery",
        "jquery.transit"      : "../bower_components/jquery.transit/jquery.transit",
        'knockout'            : '../bower_components/knockout/dist/knockout',
        'mapping'             : '../bower_components/knockout.mapping/knockout.mapping',
        'picoModal'           : '../bower_components/PicoModal/src/picoModal',
        // user defined
        'nav'                 : './modules/nav'
    },
    shim: {},
    packages: [{
        name     : "codemirror",
        location : "../bower_components/codemirror",
        main     : "lib/codemirror"
    }]
});

// this following snippet reads the data-module attribute and requires it
// from http://www.imarc.com/blog/requirejs-page-modules (rewritten a bit)
require([], function() {
    var module = document.querySelector('script[src$="require.js"]').dataset.module;
	if (module) require([module]);
});
