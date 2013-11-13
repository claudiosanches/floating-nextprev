/* global floating_nextprev_params */
(function ( $ ) {
    "use strict";

    $(function () {
        var nav_left = $( "#floating-nextprev .floating-nextprev-prev" ),
            nav_right = $( "#floating-nextprev .floating-nextprev-next" ),
            hover_distance = -20,
            initial_distance = -215,
            delay = 300,
            move_time = 300,
            opacity_time = 200;

        if ( "lik" === floating_nextprev_params.style ) {
            hover_distance = -10;
            initial_distance = -400;
            delay = 500;
            move_time = 700;
            opacity_time = delay;
        }

        // Left
        nav_left.on({
            mouseenter: function() {
                $( ".floating-nextprev-content", $( this ) ).stop().animate({
                    left: hover_distance
                }, move_time );

                $( ".floating-nextprev-content strong, .floating-nextprev-content span", $( this ) ).stop().delay( delay ).animate({
                    opacity: 1
                }, opacity_time );
            },
            mouseleave: function() {
                $( ".floating-nextprev-content", $( this ) ).stop().animate({
                    left: initial_distance
                }, move_time );

                $( ".floating-nextprev-content strong, .floating-nextprev-content span", $( this )).stop().animate({
                    opacity: 0
                }, opacity_time );
            }
        });

        // Right
        nav_right.on({
            mouseenter: function() {
                $( ".floating-nextprev-content", $( this ) ).stop().animate({
                    right: hover_distance
                }, move_time );

                $( ".floating-nextprev-content strong, .floating-nextprev-content span", $( this ) ).stop().delay( delay ).animate({
                    opacity: 1
                }, opacity_time );
            },
            mouseleave: function() {
                $( ".floating-nextprev-content", $( this ) ).stop().animate({
                    right: initial_distance
                }, move_time );

                $( ".floating-nextprev-content strong, .floating-nextprev-content span", $( this ) ).stop().animate({
                    opacity: 0
                }, opacity_time );
            }
        });
    });
}( jQuery ));
