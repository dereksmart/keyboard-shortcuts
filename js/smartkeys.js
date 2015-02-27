jQuery(document).ready(function($){

    var pressedKeys = [];

    onkeydown = onkeyup = function(e){
        e = e || event; // deal with IE

        pressedKeys[e.keyCode] = e.type == 'keydown';

        ////////////////////////////
        // Jetpack: Omnisearch
        // cmd + shift + s
        ////////////////////////////
        if ( pressedKeys[16] && pressedKeys[83] && pressedKeys[91] ) {
            $( '#adminbar-search' ).focus();
            $( 'input#adminbar-search' ).select();

            return;
        }

        ////////////////////////////
        // Jetpack: Go to settings page
        // j + e + t
        ////////////////////////////
        if ( pressedKeys[69] && pressedKeys[74] && pressedKeys[84] ) {
            if ( confirm( 'Blast off to the Jetpack Settings Page?' ) ) {
                window.location.href = smartkeys_vars.home_url + "/wp-admin/admin.php?page=jetpack_modules";
            }

            return;
        }

        ////////////////////////////
        // WordPress: Write a new post
        // p + o + s + t
        ////////////////////////////
        if ( pressedKeys[79] && pressedKeys[80] && pressedKeys[83] && pressedKeys[84] ) {
            if ( confirm( 'Take me to write a new post' ) ) {
                window.location.href = smartkeys_vars.home_url + "/wp-admin/post-new.php";
            }
            pressedKeys = [];
        }

    };

});