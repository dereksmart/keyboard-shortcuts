(function($) {
    var k = []; // The pressed key

    var defaultCommands = [];
    var userCommands    = [];

    /*
    Accepted Commands go here.
     */
    var acceptedCommands = [
        'nav home',
        'write post'
    ];

    // Init
    $(document).ready(function () {
        trackKeys();
    });


    /*
    Convert the key pressed into a letter!
     */
    function convertToCharacter(e) {
        return String.fromCharCode(e.which);
    }


    /*
    Track which keys are pressed
     */
    function trackKeys() {
        combo = '';

        onkeydown = function(e){
            e = e || event; // deal with IE
            k[e.keyCode] = e.type == 'keydown';

            // Shift is the magic key, press thrice to unlock smartness
            if ( e.shiftKey ) {
                combo += 'go';
            } else {
                combo = '';
            }

            // The magic key was pressed thrice! gogogo!
            if ( combo === 'gogogo' ) {
                smartPrompt();
                return;
            }

            keyboardShortcuts();
        };

        onkeyup = function(e){
            e = e || event; // deal with IE
            k[e.keyCode] = e.type == 'keydown';

            keyboardShortcuts();
        };

        console.log(combo);
    }

    /*
    Activates the smart prompt
    If input is empty or not in accepted array, give them another chance.
    If the prompt is accepted, do something.
     */
    function smartPrompt() {
        var userInput = prompt( 'What would you like to do? \n\n' + acceptedCommands.join().replace( ",", "\n" ) );
        var isAccepted = acceptedCommands.indexOf( userInput ) == 0;

        // If the command is not accepted, try again?
        // @todo show them a list of accepted
        if ( userInput == '' || userInput && ! isAccepted ) {
            if ( confirm( 'That command was not found, try again?' ) ) {
                smartPrompt();
            }
        }

        // Woot! Command accepted: do something
        if ( isAccepted ) {
            console.log(userInput);
            window.location.href = smartkeys_master_vars.home_url;
        }
    }

    ////////////////////////////
    // Keyboard Shortcuts
    ////////////////////////////
    function keyboardShortcuts() {
        // Jetpack: Omnisearch -- cmd / shift / s
        if ( k[16] && k[83] && k[91] ) {
            $( '#adminbar-search' ).focus();
            $( 'input#adminbar-search' ).select();

            k = [];
        }

        // Jetpack: Go to settings page -- j / e / t
        if ( k[69] && k[74] && k[84] ) {
            if ( confirm( 'Blast off to the Jetpack Settings Page?' ) ) {
                window.location.href = smartkeys_master_vars.home_url + "/wp-admin/admin.php?page=jetpack_modules";
            }

            k = [];
        }

        // WordPress: Write a new post -- p / o / s / t
        if ( k[79] && k[80] && k[83] && k[84] ) {
            if ( confirm( 'Take me to write a new post' ) ) {
                window.location.href = smartkeys_master_vars.home_url + "/wp-admin/post-new.php";
            }
            k = [];
        }
    }

})(jQuery);

