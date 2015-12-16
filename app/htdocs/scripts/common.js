"use strict";

require.config({
    paths: {
        'jquery'                     : "../bower_components/jquery/dist/jquery",
        "jquery.transit"             : "../bower_components/jquery.transit/jquery.transit",
        'knockout'                   : '../bower_components/knockout/dist/knockout.debug',
        'knockout-validation'        : '../bower_components/knockout-validation/dist/knockout.validation.min',
        'mapping'                    : '../bower_components/knockout.mapping/knockout.mapping',
        'picoModal'                  : '../bower_components/PicoModal/src/picoModal',
        "blueimp-file-upload"        : "../bower_components/blueimp-file-upload/js/jquery.fileupload",
        "jquery.ui.widget"           : "../bower_components/blueimp-file-upload/js/vendor/jquery.ui.widget",
        "slick-carousel"             : "../bower_components/slick-carousel/slick/slick",
        "featherlight"               : "../bower_components/featherlight/src/featherlight",
        "domReady"                   : "./modules/domReady",
        "historyjs"                    : "../bower_components/history.js/scripts/bundled-uncompressed/html4+html5/native.history",
        // user defined
        'nav'                        : './modules/nav'
    },
    shim: {
        'featherlight' : {
            deps : ['jquery']
        }
    },
    packages: [{
        name     : "codemirror",
        location : "../bower_components/codemirror",
        main     : "lib/codemirror"
    }]
});

// this following snippet reads the data-module attribute and requires it
// from http://www.imarc.com/blog/requirejs-page-modules (rewritten a bit 
// and loads nav too)
require(['jquery','jquery.transit','nav'], function(common){

    var nav = require('nav'),
        module = document.querySelector('script[src$="require.js"]').dataset.module;
	if (module) require([module]);
    nav();
});
