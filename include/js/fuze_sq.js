/*
 *  File Name:      fuze_Sq.js                
 *  Date Created:   24/03/2014                        
 *  Created By:     Robert K                        
 *  Version:        1.0                             
 *  Last Modified:  24/03/2014 
 *  Description:    file will be used to declare the js functionality of the sq                         
 *
 */


$(document).ready(function() {
//This to run when the document loads    
    $("#advancedSqContainer, #planAvailability").hide();

//Toggle the visibility of the advanced Service Qualification
    $("#toggleAdvanced").click(function() {
        toggleAdvanced();
    });

//Run the simple service qualification when more than six characters are entered
    $("#simpleSq").keypress(function(e) {
        if (e.keyCode == '13')
            e.preventDefault();
    });

    $("#simpleSq").keyup(function(x) {
        var simpleAddress = $.trim($("#simpleSq").val()).toLowerCase();

        if (6 < simpleAddress.length) {
            $("#planAvailability").hide();
            runSimpleSq();
        }
    }).delay(900);

//Run advanced Service Qualification    
    $("#checkAdvanced").click(function() {
        $("#planAvailability").hide();
        runAdvancedSq();
    });
//check address for details
    $("#results").change(function() {
        checkAddress();
        $("#planAvailability").show();
    });

//hide the buy now section
    $("#fuze_buy_now").hide();
    $("#fuze_buy_now_offnet").hide("fast");
});


/*runSimpleSq runs the SQ using data entered in the SQ textarea
 * @param(void)
 * @return (void)
 */
function runSimpleSq() {
    var simpleAddress = $.trim($("#simpleSq").val()).toLowerCase();

    if (simpleAddress.length == 0) {
        $("#resultsOptions").empty();
        $("#planAvailability").hide();
    }
    else if (simpleAddress.length > 6) {
        $("#resultsOptions").empty();
        //run ajax to query back end for information
        $.ajax({
            type: "POST",
            url: FUZEconnectSQ.ajaxUrl,
            async: true,
            dataType: "json",
            data: {
                "action": "simpleSq",
                "address": simpleAddress
            },
            beforeSend: function() {
                if ($("#fuze_buy_now_offnet").is(":visible")) {
                    $("#fuze_buy_now_offnet").hide("fast");
                }
                showSection("#results");
            },
            statusCode: {
                400: function() {
                    $("#resultSpan").html("Sorry incorrect request, Please try again");
                },
                500: function() {
                    $("#resultSpan").html("Sorry, looks like we have an issue. Please try call FUZEconnect for a service Qualification");
                },
                200: fuzeSuccessSimple
            }
        });
    }
}

//fuzeSuccessSimple will hold the function to be called to display the results of a simple SQ
var fuzeSuccessSimple = function(jsonResp) {

    
    var resultSpanText = $("#resultSpan");
    var resultOptions = $("#resultsOptions");

    if (jsonResp.found < 0) {
        resultOptions.empty();
        resultSpanText.html("Sorry, no results found. Please try the advanced search");
    }
    else if (jsonResp.found > 0) {
        $("#fuze_buy_now_offnet").hide("fast");
        resultOptions.empty();
        resultSpanText.html("Select your address below to see what services are available");
        resultSpanText.html("<br/>");
        displaySqResults(jsonResp);
    }
    else if (jsonResp.found == 0) {
        resultOptions.empty();
        displayOffNetResults();
    }
};

//fuzeSuccessAdvanced will hold the function to be called to display the results of the advanced SQ
var fuzeSuccessAdvanced = function(jsonResp) {
    
    var resultSpanText = $("#resultSpan");
    var resultOptions = $("#resultsOptions");

    if (jsonResp.found == 0) {
        resultOptions.empty();
        displayOffNetResults();
    }
    else if (jsonResp.found > 0 && jsonResp.found < 10) {
        $("#fuze_buy_now_offnet").hide("fast");
        resultOptions.empty();
        resultSpanText.html("Select your address below to see what services are available");
        resultSpanText.html("<br/>");
        displaySqResults(jsonResp);
    }
    else if (jsonResp.found > 10) {
        $("#fuze_buy_now_offnet").hide("fast");
        resultOptions.empty();
        resultSpanText.html("Too many addresses, please enter more information");
    }
};

/*runAdvancedSq runs the SQ using data entered in the advanced text feilds
 * @param(void)
 * @return (void)
 */
function runAdvancedSq() {

    var temp_lot, temp_street_num, temp_street_name, temp_suburb, temp_postcode;
    var lot, street_num, street_name, suburb, postcode;

    temp_lot = $.trim($("#lot").val());
    temp_street_num = $.trim($("#street_num").val());
    temp_street_name = $.trim($("#street_name").val());
    temp_suburb = $.trim($("#suburb").val());
    temp_postcode = $.trim($("#postcode").val());

//If the feild is not entered then the value null is used
    temp_lot.length > 0 ? lot = temp_lot : lot = "null";
    temp_street_num.length > 0 ? street_num = temp_street_num : street_num = "null";
    temp_street_name.length > 0 ? street_name = temp_street_name : street_name = "null";
    temp_suburb.length > 0 ? suburb = temp_suburb : suburb = "null";
    temp_postcode.length > 0 ? postcode = temp_postcode : postcode = "null";
    
    var dataArray = {
        "action": "advancedSq",
        "lot": lot,
        "street_num": street_num,
        "street_name": street_name,
        "suburb": suburb,
        "postcode": postcode
    };
    $.ajax({
        type: "POST",
        url: FUZEconnectSQ.ajaxUrl,
        async: true,
        dataType: "json",
        data: dataArray,
        beforeSend: function() {
            if ($("#fuze_buy_now_offnet").is(":visible")) {
                $("#fuze_buy_now_offnet").hide("fast");
            }
            showSection("#results");
        },
        statusCode: {
            400: function() {
                console.log("Bad request");
                $("#resultSpan").html("Sorry incorrect request, Please try again");
            },
            200: fuzeSuccessAdvanced,
            500: function (){
                $("#resultSpan").html("Sorry, looks like we're having an issue. Please call FUZEconnect for a service Qualification");
            }
        }
    });

}

/*
 * showSection will be used to show a certain section/ div the the SQ page
 * @param {string}
 * @return {void}
 */
function showSection(sectionSelector) {
    $(sectionSelector).show();
}

/*
 * displaySqResults will accept the JSON results and display at most 5 results
 * if the search parameter results in a property found
 * @param {JSON}
 * @return {void}
 */
function displaySqResults(sqJSON) {
      
    if (typeof sqJSON.result == "undefined") {
        //This will show the results of an Simple SQ because the results won't have a result variable
        $.each(sqJSON.sample, function(i, val) {
            $("#resultsOptions").append("<input class='address' type='radio' name='address' value='" + i + "'/>" + val);
            $("#resultsOptions").append("<br />");
        });
        $("#resultsOptions").append("<br/>Can't see your address? Try our advanced search above");
    }
    else if (typeof sqJSON.sample == "undefined") {
        //This will show the results of an Advanced SQ because the results won't have a sample variable
        $.each(sqJSON.result, function(i, val) {

            $("#resultsOptions").append("<input class='address' type='radio' name='address' value='" + i + "'/>" + val);
            $("#resultsOptions").append("<br />");
        });
        $("#resultsOptions").append("<br/>Can't see your address? Try adding more search parameters");

    }
}

/**displayOffNetResults will show the link to tell the user that they're off Net
 *  and let the application know that the customer is offnet
 * @param {void} 
 * @returns {void}
 */
function displayOffNetResults() {
    $("#fuze_buy_now").hide();
    $("#resultsOptions, #resultSpan").empty();
    $("#fuze_buy_now_offnet").show("fast").delay(1000);
    var offNetApp = FUZEconnectSQ.siteUrl+"/custom/Fuzeapp.php?offNet=true";
    $("#fuzeBuyNowLinkOffnet").attr("href",offNetApp);
}


/*checkAddress will take the address selected and check what services are available 
 * for the address.
 * @param   {void}
 * @return  {void}
 */
function checkAddress()  {
    //Ensure that Property ID is a number otherwisea 404 http code will be returned by the API
    var selectedAddress = parseInt($(".address:checked").val());

    if (isNaN(selectedAddress)) {
        $("#planAvailability").empty();
        $("#planAvailability").html("<strong>Invalid Property Selected</strong>, Please restart the Service Qualification");
    }
    else {
        $.ajax({
            type: "POST",
            async: true,
            url: FUZEconnectSQ.ajaxUrl,
            dataType: "json",
            data: {
                "action": "fuzeAvailability",
                "selectedAddress": selectedAddress
            },
            beforeSend: function() {
                console.log("Selected address ID is " + selectedAddress);
                $("#planTable-Network, #planTable-Speed, #planTable-ready").empty();
                //hide the input feilds
                $("#simpleSqContainer, #advancedSqContainer").hide();
            },
            statusCode: {
                500: function() {
                    $("#fuze_SqContainer").html(" Oops Something went wrong somewhere. Please check again in a few hours. Thank you");
                },
                200: displayPlanDetails
            }
        });
    }

}

//holds the function that runs on http code 200 in the ajax request in checkAddress()
var displayPlanDetails = function(jsonResp) {

    $("#planTable-Network, #planTable-Speed, #planTable-ready").html("");

    if (typeof jsonResp.error != "undefined") {
        console.log("There was an error in the code");
    }
    else {
        $("#planAvailability").show();
        console.log("This is the response in displayPlanDetails --> " + jsonResp);
        $("#planTable-Network").html("<strong>" + formatNetwork(jsonResp.connection_type) + "</strong>");
        $("#planTable-Speed").html(jsonResp.max_speed + "Mbps");
        if (jsonResp.connected || jsonResp.lead_in)
            $("#planTable-ready").html("Yes");
        else
            $("#planTable-ready").html("No");

        $("#fuze_buy_now").show("slow");
        var buyNowLink = FUZEconnectSQ.siteUrl + "/custom/Fuzeapp.php";
        $("#fuzeBuyNowLink").attr("href", buyNowLink);


    }

};

/*
 * toggleAdvanced will show or hide the advanced SQ section
 * if the advanced section is visible then the simple SQ becomes readonly
 * @param {void}
 * @return {void}
 */
function toggleAdvanced() {
    var advancedSection = $("#advancedSqContainer");
    if (advancedSection.is(":visible")) {
        $("#simpleSq").removeAttr("disabled");
        advancedSection.hide();
    }
    else {
        $("#simpleSq").attr("disabled", "true");
        $("#simpleSq").attr("value", " ");
        advancedSection.show();
    }
}

/*
 * formatNetwork will change the network type to a user friendly description
 * @param(string)
 * @return {string}
 */
function formatNetwork(networkType) {
    var onNetRegex = /on-net/gi;
    if (networkType == "AllOptics") {
        return "Fibre";
    }
    else if (networkType == "Wave7") {
        return "Fibre";
    }
    else if (networkType == "DOCSIS 1") {
        return "Cable";
    }
    else if (networkType == "DOCSIS 2") {
        return "Cable";
    }
    else if (networkType == "VDSL") {
        return "VDSL";
    }
    else if (networkType == "Eth") {
        return "Ethernet";
    }
    else if (networkType == "FttH") {
        return "Ethernet";
    }else if(onNetRegex.test(networkType)){
        return "OnNet ADSL2+";
    }
}

