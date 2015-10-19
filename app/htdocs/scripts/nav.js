"use strict";

(function($){

    $('nav ul li').click(function(e) {

        $('nav img').transition({'-webkit-transform' : 'rotate('+ e.target.dataset.rotation +'deg)',
                '-moz-transform' : 'rotate('+ e.target.dataset.rotation +'deg)',
                '-ms-transform' : 'rotate('+ e.target.dataset.rotation +'deg)',
                'transform' : 'rotate('+ e.target.dataset.rotation +'deg)'});
    });


})(jQuery);


