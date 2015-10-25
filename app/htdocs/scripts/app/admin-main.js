"use strict";

require(['codemirror','codemirror/keymap/vim'], function(CodeMirror){

    var editer = document.getElementById('editor'),

        options = {
            lineNumbers: true,
            mode: "htmlmixed",
            theme: 'solarized',
            keyMap: "vim"
        },

        cm = CodeMirror.fromTextArea(editer, options);

});
