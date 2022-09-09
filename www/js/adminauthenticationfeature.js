/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Engelbert
 * #####################################################################################################################
 * This file is part of AccessV2.
 *
 * AccessV2 is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 * AccessV2 is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *     along with AccessV2.  If not, see <http://www.gnu.org/licenses/>.
 * #####################################################################################################################
 **/

/**
 * Click Event for the Save SubFeatures for Authentication Button
 * @var output : Array containing the Authentication Scheme Name, the SubFeatures and a Boolean
 *  {
 *      "Authentication": "Authentication-Name",
 *      "SubFeature-Name": true/false,
 *      "SubFeature-Name": true/false,
 *      ...
 *  }
 *  The Boolean is True if the Authentication Scheme has the SubFeature, else it's False
 */
$("#system-container").on("click", "button", function() {
    var output = {};
    output['Authentication'] = $("#authName").text();
    $('.subfeature').each(function(){
        var inAuth = $(this).children(':last-child').attr('class');
        if(inAuth == 'in-auth'){
            output[$(this).attr('id')] = true;
        }else
        if(inAuth == 'not-in-auth'){
            output[$(this).attr('id')] = false;
        }
    });

    // Send the Data via AJAX and return to the Overview Page
    $.ajax({
        type: 'POST',
        url: '?AdminEditAuthenticationFeature',
        data: {'json': JSON.stringify(output)}
    }).done(function(data){
        window.location.href="?AdminAuthenticationFeatureOverview";
    }).fail(function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
    });
});

/**
 * Set Click-Listeners for the Images to change them
 */
$("#system-container").on("click", "img", function(e){
    if($(this).attr('class') == "in-auth"){
        $(this).attr('src', 'img/dash.png');
        $(this).attr('class', 'not-in-auth');
    }else
    if($(this).attr('class') == "not-in-auth"){
        $(this).attr('src', 'img/cross.png');
        $(this).attr('class', 'in-auth');
    }
});