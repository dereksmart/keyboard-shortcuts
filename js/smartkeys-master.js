(function($, command, action) {
    var k = []; // The pressed key

    /*
    Accepted Commands
     */
    var commandsActions = smartkeys_master_vars.prompt_commands;
    //var jetpackCommands = smartkeys_master_vars.jetpack_commands;

    console.log( commandsActions );

    // Init
    $(document).ready(function () {
        trackKeys();
        searchInit();
    });

    // Little function to check if a string ends with something
    function endsWith(str, suffix) {
        return str.indexOf(suffix, str.length - suffix.length) !== -1;
    }

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
        onkeydown = function(e){
            e = e || event; // deal with IE
            k[e.keyCode] = e.type == 'keydown';

            // J is the magic key, press thrice to unlock smartness
            if ( e.shiftKey ) {
				smartkeys_master_vars.currentCombo += 'go';
            } else if ( k[74] ) {
				smartkeys_master_vars.currentCombo += 'jet'
            } else if ( k[17] ) {
                smartkeys_master_vars.currentCombo += 'larry'
            } else {
				smartkeys_master_vars.currentCombo = '';
            }
            // The magic key was pressed thrice! gogogo!
            if ( smartkeys_master_vars.currentCombo === 'gogogo' ) {
                //smartPrompt();
                //return;
            } else if ( smartkeys_master_vars.currentCombo === 'jetjetjet' ) {
                //jetPrompt();
                //return;
            } else if ( smartkeys_master_vars.currentCombo === 'larrylarry' ) {
                larryBird();
                return;
            }

            //keyboardShortcuts();
        };

        onkeyup = function(e){
            e = e || event; // deal with IE
            k[e.keyCode] = e.type == 'keydown';

            //keyboardShortcuts();
        };
    }

    /*
    Larry Bird for the 3!!!
    If input is empty or not in accepted array, give them another chance.
    If the prompt is accepted, do something.
     */
    function larryBird() {
        smartkeys_master_vars.currentCombo = '';

        $( '.larry-bird' ).click();
        $( 'input#larry-input' ).select();
    }

    function searchInit() {
        // Search commands
        $( '#larry-input' ).on( 'keydown search', function ( event ) {
            var code = event.keyCode;

            // We want to be able to arrow through the dropdown (down and up)
            if ( ! code[40] || ! code[38] ) {
                var term = $(this).val();
                smartSearch(term);
            }
        });
    }

    function smartSearch( term ) {
        var htmlList = '', backupResults = '', inputList = '', i, searchTerm;

        for ( i = 0; i < commandsActions.length; i++ ) {
            command    = commandsActions[i].name;
            action     = commandsActions[i].action;
            searchTerm = term.toLowerCase();

            // Build the backup results? there's a better way here.
            backupResults += '<li class="smart-result"><a href="' + action + '">' + commandsActions[i].name + '</a></li>';

            // Only show submit and results when they start typing
            if ( searchTerm.length >= 1 ) {
                $( '#smart-results, #for-the-three').show();
                $( '#backup-results').hide();
            } else {
                $( '#for-the-three').hide();
            }

            // If there are any matches in the word, show it.
            if ( command.indexOf( searchTerm ) !== -1 ) {
                htmlList  += '<li class="smart-result"><a href="' + action + '">' + commandsActions[i].name + '</a></li>';
                inputList += '<option value="' + commandsActions[i].name + '">';
                $( '#larry-bird-form-list' ).html( inputList );
            }

            // Spit out the results
            $( '#smart-results' ).html( htmlList );
            $( '#backup-results').html( backupResults );
        }

        // Larry can't find it :(
        // At least he tries to help.
        if ( '' === htmlList ) {
            $( '#for-the-three' ).hide();
            $( '#smart-results' ).html( '<span class="smart-result">Larry can\'t find ' + term
                + '</span><br><h3>Examples: </h3>' + command
                + '<br><a class="see-all" style="cursor: pointer">see all</a>' );

            // Show the backup results.
            $( '.see-all' ).click( function() {
                $( '#backup-results').show();
                $( '#smart-results, #for-the-three').hide();
                $( 'input#larry-input' ).val('').select();
            });
        }

        // Listen for the form to be submitted
        var form = document.getElementById( 'larry-bird-form' );
        if ( form.attachEvent) {
            form.attachEvent( "submit", processForm );
        } else {
            form.addEventListener( "submit", processForm );
        }
    }

    // Process the form
    function processForm(e) {
        if ( e.preventDefault ) e.preventDefault();
        var input = $( '#larry-input').val(), i;
        var url = '';

        // Find the action based on the input
        _.each( commandsActions, function( cmdAct ) {
            if ( cmdAct.name == input ) {
                url += cmdAct.action;
            }
        });

        // Navigate to the page!
        window.location.href = smartkeys_master_vars.home_url + '/wp-admin/' + url;

        // You must return false to prevent the default form behavior
        return false;
    }

/*
    function smartPrompt() {
		smartkeys_master_vars.currentCombo = ''; // resets currentCombo
        var newTab     = '_self';
        var userInput  = prompt( 'Where do you want to go? \n\n' + commandsActions.command.join().replace(/,/g, "\n" ) );
        var hasNew     = userInput.search( /(new)/ );
        var isAccepted = false;

        // Check for a " new" tab requested and strip it from the input
        if ( hasNew > -1 && endsWith( userInput, ' new' ) ) {
            newTab = '_blank';
            userInput = userInput.replace( ' new' , '' );
        }

        if ( _.contains( commandsActions.command, userInput ) ) {
            isAccepted = true;
        }

        // If the command is not accepted, try again?
        if ( userInput == '' || ! isAccepted ) {
            if ( confirm( 'That command was not found, try again?' ) ) {
                smartPrompt();
            }
        }

        // Woot! Command accepted: do something
        if ( isAccepted ) {
            var commandKey = _.indexOf( commandsActions.command, userInput );
            var action = commandsActions.action[commandKey];

            window.open( smartkeys_master_vars.home_url + "/wp-admin/" + action, newTab );
        }
    }
*/

    /*
     Jetpack Commands!!!
     Quickly navigate to any jetpack settings area

     If input is empty or not in accepted array, give them another chance.
     If the prompt is accepted, do something.
     */
/*
    function jetPrompt() {
		currentCombo = ''; // resets currentCombo
        var newTab     = '_self';
        var userInput  = '';
        var userInput  = prompt( 'Blast off where? \n\n' + jetpackCommands.command.join().replace(/,/g, "\n" ) );
        var hasNew     = userInput.search( /(new)/ );
        var isAccepted = false;

        // Check for a " new" tab requested and strip it from the input
        if ( hasNew > -1 && endsWith( userInput, ' new' ) ) {
            newTab = '_blank';
            userInput = userInput.replace( ' new' , '' );
        }

        if ( _.contains( jetpackCommands.command, userInput ) ) {
            isAccepted = true;
        }

        // If the command is not accepted, try again?
        if ( userInput == '' || ! isAccepted ) {
            if ( confirm( 'That command was not found, try again?' ) ) {
                smartPrompt();
            }
        }

        // Woot! Command accepted: do something
        if ( isAccepted ) {
            var commandKey = _.indexOf( jetpackCommands.command, userInput );
            var action = jetpackCommands.action[commandKey];

            window.open( action, newTab );
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
*/
})(jQuery);

