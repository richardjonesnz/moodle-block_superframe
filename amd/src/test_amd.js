define(['jquery'], function($) {
    return {
        init: function(name) {
            // Put whatever you like here. $ is available
            // to you as normal.
            $(".block_superframe_heading").click(function() {
                alert("Hello " + name + " you clicked");
            });
        }
    };
});