<?php

/*
 *This file will handle displaying all the html required of this plugin
 */

/*fuze_show_sq contains the html content of the SQ application and 
 * will be called when the SQ needs to be shown on the page
 * @param {void}
 * @return {bool} true
 */
function fuze_show_sq(){
    ob_start();?> 
    <div id="fuze_SqContainer" class="wrap">
            <div id="simpleSqContainer">
                <form id="simpleSqForm" name="simpleSqForm"  autocomplete="off">
                    <table id="simpleSqTable">
                        <tr class="search"> 
                            <td>Enter Address:</td>
                            <td><input type="text" id="simpleSqUnit" placeholder="Unit # or Apt #" /></td>
                            <td><textarea cols="50" rows="1" name="simpleSq" id="simpleSq" placeholder="Eg. 100 Fuze Street, Sydney "></textarea></td>
                        </tr>
                    </table>            
                </form>
            </div>
            <a href="#" id="toggleAdvanced" name="defaultToggle">Advanced Search</a>
            <div id="advancedSqContainer">
                <fieldset>
                    <legend>Service Address</legend>
                    <form id="advancedSqForm" name="advancedSqForm" autocomplete="off">
                        <table id="advancedSqTable">
                            <tr>
                                <td>lot:</td>
                                <td><input name="lot" id="lot" type="text" placeholder="lot #" /></td>
                            </tr>
                            <tr>
                                <td>Street Number:</td>
                                <td><input name="street_num" id="street_num" type="text" placeholder="Street #"/></td>
                            </tr>
                            <tr>
                                <td>Street Name:</td>
                                <td><input name="street_name" id="street_name" type="text" placeholder="Street Name"/></td>
                            </tr>
                            <tr>
                                <td>Suburb:</td>
                                <td><input name="suburb" id="suburb" type="text" placeholder="suburb"/></td>
                            </tr>
                            <tr>
                                <td>Postcode:</td>
                                <td><input name="postcode" id="postcode" type="text" placeholder="postcode"/></td>
                            </tr>               
                            <tr>
                                <td colspan="2">
                                    <input type="button" value="check Address" id="checkAdvanced" name="checkAdvanced"/>
                            </tr>                
                        </table>
                    </form>
                </fieldset>
            </div>

            <div id="results">

                <span id='resultSpan'></span>
                <div id="resultsOptions">

                </div>
            </div>

            <div id="planAvailability">
                <p><strong>Service Details</strong></p>
                <table id="planAvailability-table">
                    <tr>
                        <td class="planTableLabel">
                            Network Type:
                        </td>
                        <td>
                            <span id="planTable-Network"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="planTableLabel">
                            Maximum Speeds:
                        </td>
                        <td>
                            <span id="planTable-Speed"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="planTableLabel">
                            Internet Ready:
                        </td>
                        <td>
                            <span id="planTable-ready"></span>
                        </td>
                    </tr>
                </table>  
            </div>
            
            <div id="fuze_buy_now">
                <!--ONNet Applications-->
                <br/>
                Now that you're onNet click on the button below to begin your Internet application. Your address will be prefilled
                <button><a class="fancybox-iframe button" id='fuzeBuyNowLink' href="#">Buy Now</a></button>
            </div>
            <div id="fuze_buy_now_offnet">
                Looks like your address is not in one of our OnNet areas. We can still provide you with our OffNet plans. 
                Please fill in the online application and the Service Team will run an OffNet Service Qualification
                <button><a class="fancybox-iframe button" id='fuzeBuyNowLinkOffnet' href="#">Buy Now</a></button>
            </div>
        </div>
<?php
return ob_get_clean();
}

/*this will add the shortcode [fuze_sq] that will be used to integrate the html involved
 * in creating the sq forms and divs 
 */
add_shortcode('fuze_sq', 'fuze_show_sq');

?>
