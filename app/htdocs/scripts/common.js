require.config({
    shim: {},
    paths: {
        "blueimp-file-upload": "../../../bower_components/blueimp-file-upload/js/jquery.fileupload",
        jquery: "../../../bower_components/jquery/dist/jquery",
        "jquery.transit": "../../../bower_components/jquery.transit/jquery.transit",
    },
    packages: [{
        name: "codemirror",
        location: "../../../bower_components/codemirror",
        main: "lib/codemirror"
    }]
});
