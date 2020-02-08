<?php
//basic service qualification
define("BASE_URL_SIMPLE", "http://domain/sq/search/basic/");
//Advanced Service Qualification
define("BASE_URL_ADVANCED", "http://domain/sq/search/advanced?");

//Get dwelling details in JSON format or Plan availability for an address
//format is http://domain/sq/dwelling/XXXX/address for the address details in JSON
//format is http://domain/sq/dwelling/XXXX/availability for plan details 
define("BASE_URL_DWELLING", "http://domain/sq/dwelling/");


/* * ********************************************************************************************
 * getSimple sends a cURL request to SQ API to perform a simple sq request
 * @param (string)
 * @returns (list)
 * * ********************************************************************************************
 */
function getSimple($searchParam) {

    $curl_url = BASE_URL_SIMPLE . $searchParam;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    $curlinfo = curl_getinfo($ch);

    return array($curlinfo, $data);
}

/* * ********************************************************************************************
 *  getAdvanced sends a cURL request to SQ API to perform an advanced sq request
 * @param (string)
 * @returns (list)
 * **********************************************************************************************
 */
function getAdvanced($advancedParam) {
    $curl_url = BASE_URL_ADVANCED;

    $firstParam = true;

    if (null != $advancedParam["lot"]) {
        if ($firstParam) {
            $curl_url .= "lot=" . rawurlencode($advancedParam["lot"]);
            $firstParam = false;
        } else {
            $curl_url .= "&lot=" . rawurlencode($advancedParam["lot"]);
        }
    }

    if (null != $advancedParam["street_num"]) {
        if ($firstParam) {
            $curl_url .= "street_num=" . rawurlencode($advancedParam["street_num"]);
            $firstParam = false;
        }
        else
            $curl_url .= "&street_num=" . rawurlencode($advancedParam["street_num"]);
    }

    if (null != $advancedParam["street_name"]) {
        if ($firstParam) {
            $curl_url .= "street_name=" . rawurlencode($advancedParam["street_name"]);
            $firstParam = false;
        }
        else
            $curl_url .= "&street_name=" . rawurlencode($advancedParam["street_name"]);
    }

    if (null != $advancedParam["suburb"]) {
        if ($firstParam) {
            $curl_url .= "suburb=" . rawurlencode($advancedParam["suburb"]);
            $firstParam = false;
        }
        else
            $curl_url .= "&suburb=" . rawurlencode($advancedParam["suburb"]);
    }

    if (null != $advancedParam["postcode"]) {
        if ($firstParam) {
            $curl_url .= "postcode=" . rawurlencode($advancedParam["postcode"]);
            $firstParam = false;
        }
        else
            $curl_url .= "&postcode=" . rawurlencode($advancedParam["postcode"]);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    $curlinfo = curl_getinfo($ch);
    

    return array($curlinfo, $data);
}

/* * ********************************************************************************************
 * getDetail will take the ID of a proprty and return whats available at the address
 * @param (string)
 * @return (JSON/string) 
 * * ********************************************************************************************
 */
function getPlanDetails($propId) {
    $curl_url = BASE_URL_DWELLING.rawurlencode($propId)."/availability";
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    $curlinfo = curl_getinfo($ch);
    
    return array($curlinfo, $data);
}

/* * ********************************************************************************************
 * getPropDetails will return the details for a property as a json object and not a string
 * @param (string)
 * @return (JSON/string) 
 * **********************************************************************************************
 */
function getPropertyJson($propId) {
    $curl_url = BASE_URL_DWELLING.rawurlencode($propId)."/address";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    $curlinfo = curl_getinfo($ch);

    return array($curlinfo, $data);
}

?>
