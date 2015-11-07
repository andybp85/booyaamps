"use strict";

require(['jquery', 'knockout','nav', 'picoModal', 'knockout-validation'], function($, ko, nav, picoModal, validation){

    nav();

    function Amp(data){
        this.id = ko.observable(data.id);
        this.title = ko.observable(data.title);
        this.desc = ko.observable(data.description);
    }

    function ViewModel(){
        // Properties
        var self = this,
            newAmp = new Amp({
                        id: null,
                        title: '',
                        desc: ''
                     });
        self.amps = ko.observableArray([newAmp]);
        self.selectedAmp = ko.observable(newAmp);


        // Load
        $.getJSON("/galleries/amp", function(data) {
            $.map(data, function(item) { self.amps.push(new Amp(item)); });
        });

        // Methods
        self.save = function(){
            var postData = {"table" : "entries", 'data' : { 'title' : self.selectedAmp().title,
                                                        'description' : self.selectedAmp().desc,
                                                        'id' : self.selectedAmp().id }};

            $.post('/admin/galleryEntry', postData, function(res) {
                if (res === '1') {
                   $('#success').fadeIn().delay(5000).fadeOut();
                } else {
                   picoModal("Error: " + res).show();
                }
            }).fail(function(data){
                picoModal("Error: " + data).show();
            });;
        };

    }

    ko.applyBindings(new ViewModel());
});

