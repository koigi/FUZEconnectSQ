<?php
session_start();
require_once('backend.php');
/*
 * simpleSq will handle the ajax requests for the Fuzeconnect simple
 * Service qualification
 */

function simpleSq() {

    $simpleSq = rawurlencode($_POST['address']);
    list($curlInfo, $simpleSqResult) = getSimple($simpleSq);

    if ($curlInfo['http_code'] != 200 && $curlInfo['http_code'] != 400) {
        echo false;
    } else if ($curlInfo['http_code'] == 200) {
        echo $simpleSqResult;
    } else if ($curlInfo['http_code'] == 400) {
        echo $simpleSqResult;
    }
    die();
}

//Add the function callback to wp ajax
add_action("wp_ajax_nopriv_simpleSq", "simpleSq");


/* advancedSq will handle the ajax requests for the FUZEconnect advanced 
 * Service Qualification
 */

function advancedSq() {
    $sqParam = array(
        "lot" => null,
        "street_num" => null,
        "street_name" => null,
        "suburb" => null,
        "postcode" => null
    );
    if ("null" != ($_POST["lot"]))
        $sqParam["lot"] = $_POST["lot"];
    if ("null" != ($_POST["street_num"]))
        $sqParam["street_num"] = $_POST["street_num"];
    if ("null" != ($_POST["suburb"]))
        $sqParam["suburb"] = $_POST["suburb"];
    if ("null" != ($_POST["street_name"]))
        $sqParam["street_name"] = $_POST["street_name"];
    if ("null" != ($_POST["postcode"]))
        $sqParam["postcode"] = $_POST["postcode"];

    list ($curlInfo, $advancedSqResult) = getAdvanced($sqParam);

    if ($curlInfo['http_code'] != 200) {
        echo false;
    } else {
        echo $advancedSqResult;
    }
    die();
}

//Adds the function advancedSq to wordpresses wp ajax
add_action("wp_ajax_nopriv_advancedSq", "advancedSq");

/*
 * fuzeAvailability will check the API for what services are available in the 
 * selected address
 */

function fuzeAvailability() {

    $propId = $_POST["selectedAddress"];
    list ($curlInfo, $planAvailability) = getPlanDetails($propId);
    list($curlInfoB, $propertyParts) = getPropertyJson($propId);

    $_SESSION = array();
    //Unset the sessions before a new one is attemted to be set
    if ($curlInfo['http_code'] == 200 || $curlInfo['http_code'] == 400
            || $curlInfoB['http_code'] == 200 || $curlInfoB['http_code'] == 400) {
        $_SESSION['fuze_propId'] = $propId;
        $_SESSION['fuze_availability'] = json_decode($planAvailability, true);
        $_SESSION['fuze_address'] = json_decode($propertyParts, true); 
        
        echo $planAvailability;
    } else {
        echo "cURL Error";
    }

    die();
}

//Adds the function advancedSq to wordpresses wp ajax
add_action("wp_ajax_nopriv_fuzeAvailability", "fuzeAvailability");

function getPropParts($fuze_propId) {
    //ensure that the property is an integer and is greater than 0
    if ($fuze_propId != 0 || $fuze_propId > 0) {
        list ($curlInfo, $propertyParts ) = getPropertyJson($fuze_propId);
        if ($curlInfo['http_code'] == 200 || $curlInfo['http_code'] == 400) {
            return $propertyParts;
        }
        else{
            $errorArray = array("errorFound"=>true, "error"=> "cURL Error", "info"=> $curlInfo);
            return json_encode($errorArray);
        }
    } else {
        $errorArray = array("errorFound" => true, "error" => "Invalid Property ID");
        return json_encode($errorArray);
    }
    die();
}

add_action("wp_ajax_nopriv_getPropParts", "getPropParts");

/*printFuzeappAddress will be called on the internet application.
 * 
 */

function printFuzeappAddress() {
    ob_start();
    ?>   
    <table width="90%" border="0" cellpadding="0" cellspacing="0" class="form-table">
        <tr>
            <th width="20%"><label>Unit / Lot Number&nbsp;&nbsp;</label></th>
            <td colspan="2"><input type="text" id="txtUnitNumber" name="txtUnitNumber" size="20" /></td>
        </tr>
        <tr>
            <th><label>Street Number *</label></th>
            <td><input type="text" id="txtStreetNumber" name="txtStreetNumber" size="20" /></td>
            <td><span class="error" id="streetNumberError"></span></td>
        </tr>
        <tr>
            <th><label>Street Name *</label></th>
            <td><input type="text" id="txtStreetName" name="txtStreetName" size="20" /></td>
            <td><span class="error" id="streetNameError"></span></td>
        </tr>
        <tr>
            <th><label>Suburb *</label></th>
            <td><input type="text" id="txtSurburb" name="txtSurburb" size="20" /></td>
            <td><span class="error" id="surburbError"></span></td>
        </tr>
        <tr>
            <th><label>State *</label></th>
            <td>
                <?php
                if (isset($_SESSION['fuze_address'])) {
                    ob_start();?>
                <input type="text" readonly="readonly" id="ddlState" value="<?php echo $_SESSION['fuze_address']['state'];?>"/>
                        <?php
                        echo ob_get_clean();
                } else {
                    ob_start();
                    ?>
                    <select name="ddlState" id="ddlState">
                        <option value="1">Select</option>
                        <option value="ACT">ACT</option>
                        <option value="NSW">NSW</option>
                        <option value="NT">NT</option>
                        <option value="QLD">QLD</option>
                        <option value="SA">SA</option>
                        <option value="TAS">TAS</option>
                        <option value="VIC">VIC</option>
                        <option value="WA">WA</option>
                    </select>
                    <?php
                    echo ob_get_clean();
                }
                ?>                
            </td>
            <td><span class="error" id="stateError"></span></td>
        </tr>
        <tr>
            <th><label>Postcode *</label></th>
            <td><input type="text" id="txtPostCode" name="txtPostCode" /></td>
            <td><span class="error" id="postCodeError"></span></td>
        </tr>
    </table>

    <?php
    return ob_get_clean();
}

add_action("wp_ajax_nopriv_printFuzeappAddress", "printFuzeappAddress");
?>
