"use strict";

require.config({
    shim: {},
    paths: {
        "blueimp-file-upload": "../../../bower_components/blueimp-file-upload/js/jquery.fileupload",
        jquery: "../../../bower_components/jquery/dist/jquery",
        "jquery.transit": "../../../bower_components/jquery.transit/jquery.transit",
        'knockout': '../../../bower_components/knockout/dist/knockout',
        // user defined
        'nav': './modules/nav'
    },
    packages: [{
        name: "codemirror",
        location: "../../../bower_components/codemirror",
        main: "lib/codemirror"
    }]
});

// this following snippet reads the data-module attribute and requires it
// from http://www.imarc.com/blog/requirejs-page-modules (rewritten a bit)
require([], function() {
    var module = document.querySelector('script[src$="require.js"]').dataset.module;
	if (module) require([module]);
});
