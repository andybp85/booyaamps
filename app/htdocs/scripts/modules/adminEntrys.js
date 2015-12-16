require(['jquery','knockout','picoModal','knockout-validation','blueimp-file-upload','slick-carousel','featherlight','domReady!'],
        function($, ko, nav, picoModal, validation){
        'use strict';


    function Entry(data){
        this.id = ko.observable(data.id);
        this.title = ko.observable(data.title);
        this.desc = ko.observable(data.description);
        this.publish = ko.observable(data.publish);
        this.media = ko.observableArray([]);
        this.type = data.page;

        if (data.media) {
            var that = this;
            data.media.forEach(function(m){
                that.media.push(m);
                //console.log(m));
            });
        }
    }

    function ViewModel(){
        // Properties
        var self = this;
        self.page = ko.observable(document.querySelector('input[name="page"]').value);
        self.newEntry = new Entry({
                                'type'       : self.page,
                                'id'         : null,
                                'title'      : '',
                                'desc'       : '',
                                'publish'    : 'draft',
                                'media'      : null,
                                'pageStyles' : null
                            });
        self.entries = ko.observableArray([self.newEntry]);
        self.selectedEntry = ko.observable(self.newEntry);

        // Load

        $.getJSON("/galleries/" + self.page(), function(data) {
            $.map(data, function(item) { self.entries.push(new Entry(item)); });
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
                data.context.text('Upload failed: ' + data.jqXHR.responseText);
            },
            done: function (e, data) {
                console.log();
                $.getJSON('/admin/media/' + data.result.files[0].id, function(res){
                    self.selectedEntry().media.push( res.pop() );
                    $('div#imgCarousel').slick('slickAdd',$('div#imgCarousel').children('.slide'));
                    data.context.text('Upload finished.');
                    $('div#imgCarousel').slick('slickGoTo', $('div#imgCarousel').slick('getSlick').$slides.length - 1);
                });
            }
        });

        // Methods
        self.deleteFile = function(file, e){
            $.ajax({
                url: '/admin/media/' + file.id,
                type: 'DELETE',
                data: { "path" : file.path }
            }).done(function(res) {
                $('div#imgCarousel').slick('slickRemove',e.target.parentElement.dataset.slickIndex);
                self.selectedEntry().media.remove(file);
            }).fail(function(data) {
                picoModal("Error: " + data.responseText).show();
            });
        };

        self.save = function(){
            var postData = {'table' : "entries",
                            'data'  : {
                                'type'        : self.page(),
                                'title'       : self.selectedEntry().title,
                                'description' : self.selectedEntry().desc,
                                'pageStyles'  : self.selectedEntry().pageStyles
                               }
                            },
                url = ( self.selectedEntry().id() === null ? '/admin/galleryEntry' : '/admin/galleryEntry/' + self.selectedEntry().id());

            $.post(url, postData, function(res) {
                //TODO: Figure out $($())
                $($(e).children('.success')[0]).fadeIn().delay(5000).fadeOut();
            }).fail(function(data){
                picoModal("Error: " + data.status).show();
            });
        };

        self.updateStatus = function(e){
            var postData = {
                "table": "entries"
            };
            $.post('/admin/galleryEntry/' + self.selectedEntry().id() + '/' + $(e).children('[name=publish]')[0].value, postData, function(res) {
                $($(e).children('.success')[0]).fadeIn().delay(5000).fadeOut();
            }).fail(function(data){
                picoModal("Error: " + data.status).show();
            });
        };


/*        self.pageStyles = function(){*/

        /*};*/


    } //end ViewModel

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

    ko.bindingHandlers.slick = {
        init: function(element) {
        // args: element, valueAccessor, allBindings, viewModel, bindingContext
            $(element).slick({
                infinite: true,
                lazyLoad: 'ondemand',
                slidesToShow: 3,
                slidesToScroll: 1
            });
        },
        update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
            // This will be called once when the binding is first applied to an element,
            // and again whenever any observables/computeds that are accessed change
            var updater = valueAccessor();
            while ($(element).slick('getSlick').$slides.length > 0) {
                $(element).slick('slickRemove',0);
            }
            $(element).slick('slickAdd',$(element).children('div.slide'));
        }
    };

    var mainNode = document.getElementsByTagName('main')[0]
    ko.cleanNode(mainNode);
    ko.applyBindings(new ViewModel(), mainNode);

});

