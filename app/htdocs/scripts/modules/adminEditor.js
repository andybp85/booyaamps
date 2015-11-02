"use strict";

require(['codemirror','codemirror/keymap/vim','nav'], function(CodeMirror){

    var nav = require('nav'),
        editor = document.getElementById('editor');

    nav();

    var options = {
        lineNumbers: true,
        mode: "htmlmixed",
        theme: 'solarized',
        keyMap: "vim"
    },
    cm = CodeMirror.fromTextArea(editor, options);

});


