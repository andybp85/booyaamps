"use strict";

require(['jquery', 'knockout','nav', 'picoModal', 'knockout-validation','blueimp-file-upload','slick-carousel'],
        function($, ko, nav, picoModal, validation){

    nav();

    var page = document.querySelector('input[name="page"]').value;

    function Entry(data){
        this.id = ko.observable(data.id);
        this.title = ko.observable(data.title);
        this.desc = ko.observable(data.description);
        this.published = ko.observable(data.published);
        this.paths = ko.observableArray([]);
        this.type = page;

        if (data.paths) {
            var that = this;
            data.paths.split(',').forEach(function(path){
                that.paths.push(path);
            });
        }
    }

    function ViewModel(){
        // Properties
        var self = this,
            newEntry = new Entry({
                                'type'       : page,
                                'id'         : null,
                                'title'      : '',
                                'desc'       : '',
                                'paths'      : null,
                                'pageStyles' : null
                            });
        self.amps = ko.observableArray([newEntry]);
        self.selectedEntry = ko.observable(newEntry);


        // Load
        $.getJSON("/galleries/" + page, function(data) {
            $.map(data, function(item) { self.amps.push(new Entry(item)); });
            ;
        });

        $('#fileupload').fileupload({
            dataType: 'json',
            add: function (e, data) {
                var filetype = data.files[0].type.split('/')[0];
                if ( ! (filetype === 'image' || filetype === 'video')) {
                    picoModal('Only pictures and videos allowed').show();
                    return false;
                }
                data.context = $('<button/>').text('Upload')
                    .appendTo(document.getElementById('files'))
                    .click(function () {
                        data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                        data.submit();
                    });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .bar').css(
                    'width',
                    progress + '%'
                );
            },
            fail: function (e, data) {
                data.context.text('Upload failed: ' + data.errorThrown);
            },
            done: function (e, data) {
                if (data.result.files[0].error !== 0) {
                    data.context.text('Upload failed: ' + data.result.files[0].error);
                } else {
                    data.context.text('Upload finished.');
                }

            }
        });

        self.selectedEntry.subscribe(function(){
            $('#imgCarousel').slick();
        });


        // Methods
        self.save = function(){
            var postData = {'table' : "entries",
                            'data'  : {
                                'type'        : page,
                                'title'       : self.selectedEntry().title,
                                'description' : self.selectedEntry().desc,
                                'pageStyles'  : self.selectedEntry().pageStyles,
                                'published'   : self.selectedEntry().published
                               }
                            };

            $.post('/admin/galleryEntry/' + self.selectedEntry().id(), postData, function(res) {
                if (res === '1') {
                   $('#success').fadeIn().delay(5000).fadeOut();
                } else {
                    picoModal("Error: " + res).show();
                }
            }).fail(function(data){
                    console.log(data);
                picoModal("Error: " + data.status).show();
            });
        };

        self.updateStatus = function(){
            $.post('/admin/galleryEntry/', postData, function(res) {
                if (res === '1') {
                   $('#success').fadeIn().delay(5000).fadeOut();
                } else {
                    picoModal("Error: " + res).show();
                }
            }).fail(function(data){
                picoModal("Error: " + data.status).show();
            });
        };

/*        self.pageStyles = function(){*/

        /*};*/


    }

    // custom bindings
    ko.bindingHandlers.slideVisible = {
        init: function(element, valueAccessor) {
            // Initially set the element to be instantly visible/hidden depending on the value
            var value = valueAccessor();
            $(element).hide(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
        },
        update: function(element, valueAccessor) {
            // Whenever the value subsequently changes, slowly fade the element in or out
            var value = valueAccessor();
            ko.unwrap(value) ? $(element).slideDown() : $(element).slideUp();
        }
    };

    ko.applyBindings(new ViewModel());
});

