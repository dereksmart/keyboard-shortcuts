jQuery(document).ready(function($){

    var pressedKeys = [];

    onkeydown = onkeyup = function(e){
        e = e || event; // deal with IE

        pressedKeys[e.keyCode] = e.type == 'keydown';

        ////////////////////////////
        // Jetpack: Omnisearch
        // COMMAND + SHIFT + S
        ////////////////////////////
        if ( pressedKeys[16] && pressedKeys[83] && pressedKeys[91] ) {
            $( '#adminbar-search' ).focus();
            $( 'input#adminbar-search' ).select();

            pressedKeys = [];
        }

        ////////////////////////////
        // WordPress: Write a new post
        // p + o + s + t
        ////////////////////////////
        if ( pressedKeys[79] && pressedKeys[80] && pressedKeys[83] && pressedKeys[84] ) {
            window.location.href = keyboard_shortcut_vars.home_url + "/wp-admin/post-new.php";
            pressedKeys = [];
        }

    };

});