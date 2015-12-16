"use strict";

define(['jquery','historyjs','jquery.transit'], function($){

    return function() {

        function rotate(target, degrees) {
            $(target).transition({'-webkit-transform' : 'rotate('+ degrees +'deg)',
                        '-moz-transform' : 'rotate('+ degrees +'deg)',
                        '-ms-transform' : 'rotate('+ degrees +'deg)',
                        'transform' : 'rotate('+ degrees +'deg)'});
        }

        // on statechange deal with rotation and history
        History.Adapter.bind(window, 'statechange', function() {

            var url = History.getState().url,
                rotation = History.getState().data.rotation,
                pageModule = $('script#pageModule')[0].textContent.split('\'')[1]

            requirejs.undef(pageModule);

            if (rotation) {
               rotate('nav img', rotation);
            }
            $('main > div').load(url).css('width','0px').animate({'width' : '100%'});
        });

        // on click update the state which will trigger a change
        $('nav ul li').click(function(e) {

            var rotation = this.dataset.rotation;

            $('nav ul li').removeAttr('id');
            $(this).attr('id','thispage');

            if (rotation) {
               rotate('nav img', rotation);
            }

            History.pushState({urlPath: this.dataset.page, rotation: rotation}, $("title").text(), this.dataset.page);
        });


        // on load set state up
        var thispage = document.getElementById('thispage');

        if (thispage) {
            rotate('nav img', thispage.dataset.rotation);
        }

        History.pushState({
                        urlPath: window.location.pathname,
                        rotation: (thispage ? thispage.dataset.rotation : false)
        }, $("title").text(), History.getState().urlPath);
    };

});
