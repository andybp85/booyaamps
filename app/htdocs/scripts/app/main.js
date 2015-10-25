"use strict";

require(['jquery','jquery.transit'], function($){

    $('nav ul li').click(function(e) {

        if (e.target.dataset.rotation) {
            $('nav img').transition({'-webkit-transform' : 'rotate('+ e.target.dataset.rotation +'deg)',
                '-moz-transform' : 'rotate('+ e.target.dataset.rotation +'deg)',
                '-ms-transform' : 'rotate('+ e.target.dataset.rotation +'deg)',
                'transform' : 'rotate('+ e.target.dataset.rotation +'deg)'});
        }

        $('main > div').load(this.dataset.page).css('width','0px').animate({'width' : '100%'});

        history.pushState(null, e.target.dataset.page, e.target.dataset.page);
    });

});
