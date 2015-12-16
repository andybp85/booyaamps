"use stict";

var reqs = ['codemirror','picoModal','codemirror/keymap/vim','domReady!'],
    modes = [
        'codemirror/mode/xml/xml',
        'codemirror/mode/css/css',
        'codemirror/mode/javascript/javascript',
        'codemirror/mode/htmlmixed/htmlmixed',
        'codemirror/mode/php/php',
        'codemirror/mode/sass/sass',
        'codemirror/mode/smarty/smarty',
        'codemirror/mode/sql/sql'
    ];

require(reqs.concat(modes), function(CodeMirror, picoModal){

    var fileManager = function(dir, resolve, reject) {

        var self = this;

        self.modal;
        self.path;
        self.docList;
        self.modalContent = document.createElement('div');
        self.resolve = resolve;
        self.reject = reject;

        self.modalContent.id = "fileManager";

        $.get('files', {"file" : dir }, function(res){
            var filesString = res.split(':'),
                files = filesString[1].split('|'),
                dirTitleH2 = document.createElement('h2');

            self.path = document.createTextNode(filesString[0]),
            self.docList = document.createElement('ul');
            dirTitleH2.appendChild(self.path);

            for (var i=0; i < files.length; i++) {
                var li = document.createElement('li');
                li.innerHTML = files[i];
                self.docList.appendChild( li );
            }

            self.modalContent.appendChild(dirTitleH2);
            self.modalContent.appendChild(self.docList);

            self.modal = picoModal(self.modalContent).afterClose(function (modal) { modal.destroy(); }).show();

            // Handler for folder navigation
            self.docList.addEventListener('click', function(e){
                var path = e.target.textContent;

                if ( path === '..') {
                    var pathFolders = self.path.nodeValue.split('/');
                    pathFolders.splice(-2,2);
                    if (pathFolders.length === 1) {
                        path = '.';
                    } else {
                        path = pathFolders.join('/');
                    }
                } else {
                    path = self.path.nodeValue + path;
                }
                self.get(path);
            });


        }).fail(function(data) {
            self.error(data.responseText);
        });

        self.get = function(selection){
            $.get('files', {"file" : selection }, function(data, status, xhr){
                var contentType = xhr.getResponseHeader('Content-type').split(';')[0];
                if ( contentType === 'text/x-dir' ) {
                    var filesString = data.split(':'),
                        files = filesString[1].split('|');

                    self.path.nodeValue = filesString[0];

                    while (self.docList.firstChild) {
                        self.docList.removeChild(self.docList.firstChild);
                    }

                    for (var i=0; i < files.length; i++) {
                        var li = document.createElement('li');
                        li.innerHTML = files[i];
                        self.docList.appendChild( li );
                    }

                } else {
                    self.modal.close();
                    self.resolve( { "mode": contentType, "data" : data } );
                }
            });
        };

        self.error = function(message) {
            picoModal("Error: " + message).afterClose(function (modal) { modal.destroy(); }).show();
        };

    };

    var editor = function(){

        var self = this;

        self.cm;
        self.settings;
        self.settingsFile = '../src/editorOptions.json';
        self.file = '',
        self.folder = '.';

        $.getJSON('files', {"file" : self.settingsFile}, function(data){
            self.settings = data;
            self.cm = CodeMirror.fromTextArea(document.getElementById('editor'), data);
        });

        self.loadSettings = function(e) {
            e.preventDefault();
            self.cm.setValue(JSON.stringify(self.settings, null, 4) );
            self.cm.setOption("mode", "application/json")
            document.getElementById('cmesave').removeEventListener('click',codeEditor.saveFile);
            document.getElementById('cmesave').addEventListener('click',codeEditor.saveSettings);
        };

        self.saveSettings = function(e) {
            e.preventDefault();
            try {
                var content = JSON.parse(self.cm.getValue());
                $.post('files', {
                        "file": self.settingsFile,
                        "content": content
                }).done(function(){
                    self.settings = content;
                    document.getElementById('cmesave').removeEventListener('click',codeEditor.saveSettings);
                    document.getElementById('cmesave').addEventListener('click',codeEditor.saveFile);

                    self.cm.setValue(self.file);
                    self.success();
                }).fail(function(data) {
                    self.error(data.responseText);
                });
            } catch (e) {
                self.error(e);
            }
        };

        self.loadFile = function(){
            var promise = new Promise(function(resolve, reject){
                var mgr = new fileManager(self.folder, resolve, reject);
            });
            promise.then(function(res){
                self.cm.setOption("mode",res.mode);
                self.cm.setValue( res.data );

            });

        };

        self.saveFile = function(){

        };

        self.success = function() {
            $('.success').fadeIn().delay(5000).fadeOut();
        };

        self.error = function(message) {
            picoModal("Error: " + message).afterClose(function (modal) { modal.destroy(); }).show();
        };

    };


   var codeEditor = new editor();

   document.getElementById('cmesettings').addEventListener('click',codeEditor.loadSettings);

   document.getElementById('cmesave').addEventListener('click',codeEditor.saveFile);

   document.getElementById('cmeopen').addEventListener('click',codeEditor.loadFile);


});


