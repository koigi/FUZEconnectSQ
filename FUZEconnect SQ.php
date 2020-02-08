<?php
/*
    Plugin Name: FUZEconnect SQ 
    Plugin URI: https://fuzeconnect.com.au
    Description: Plugin for performing Service Qualifications for addresses on the fuzeconnect or Service Elements network
    Author: Robert Koigi    
    Version: 1.1
    Author URI: https://www.fuzeconnect.com.au
    License: GPL 2
*/

/*
 * PLUGIN GLOBALS
 */


/*
 * ENABLE SESSIONS FOR THE SQ
 */
function fuze_register_session(){
    if(!session_id()){
        session_start();
    }
}
add_action('init', 'fuze_register_session');

/**
 *FILE INCLUDES 
 */

//enqueues all the scripts needed for the plugin to run
require_once('include/fuze_scripts.php');
require_once('include/fuze_display_functions.php');
require_once('include/fuze_ajax.php');


?>
