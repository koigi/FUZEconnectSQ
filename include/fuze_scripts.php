<?php

/*****************************************************************************
 * fuze_enqueueSqScripts contains the calls to enqueue the appropriate files to 
 * put in the header tag whenever the SQ is implemented on a page
 * 
 * The JS files and the css files for the SQ will be in /include ie
 * https://www.fuzeconnect.com.au/wp-plugins/FUZEconnect/include
 * 
 * @param   {void}
 * @return  {void}
 * ******************************************************************************
 */
function fuze_enqueueSqScripts(){
    if(is_singular() && is_page()){
        $fuze_cssFile = plugin_dir_url(__FILE__).'/css/fuze_sq.css';
        $fuze_jsFile = plugin_dir_url(__FILE__).'/js/fuze_sq.js';
    
        wp_enqueue_script("fuze_sq.Js",$fuze_jsFile, array("jquery"), "1.0");
        wp_enqueue_style("fuze_sq.Css", $fuze_cssFile, false, "1.0");
        
        wp_localize_script("fuze_sq.Js", "FUZEconnectSQ", localizeSq());
    }
    
}
//Enqueue the scripts for the SQ to work
add_action("wp_enqueue_scripts", "fuze_enqueueSqScripts");

/*
 * localizeSq will be used to return the array variables to be accepted when 
 * localizing the script for the SQ to act as an auxiliary to the AJAX functionality
 */
function localizeSq(){
    return array(
        "siteUrl"=>get_bloginfo('url'),
        "ajaxUrl"=>admin_url("admin-ajax.php"),       
    );
}

?>
