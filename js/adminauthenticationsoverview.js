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
 * Click-Listener for the "Change Authentication Schemes" Button
 * @var output - An Array containing Authentication Scheme Names and their Categories
 *  {
 *    Authentication-Name => Category
 *    Authentication-Name => Category
 *    ...
 *  )
 */
$(".ui-button").on("click", function() {
    var output = {};

    $('.authContainer').each(function(){
        var name = $(this).children('.authName').text();
        var cat = $(this).children('.authCat').text();
        output[name] = cat;
    });

    var json = JSON.stringify(output);

    // Ajax Request to save the Changes - Reload the Page
    $.ajax({
        type: 'POST',
        url: '?AdminEditAuthentications',
        data: {'json': json}
    }).done(function(data){
        window.location.href = "?AdminAuthenticationsOverview";
    }).fail(function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
    });
});

/**
 * Set Listeners to the Images to Add or Remove Authentication Schemes
 */
$("#system-container").on("click", "img", function(e){
    if($(this).attr('class') == "remove-auth"){
        $(this).closest('div').remove();
    }else
    if($(this).attr('class') == "add-auth"){
        var div = $(this).closest('div');
        var name = "";
        var cat = "";

        div.children('input').each(function(){
            if($(this).attr('name') == "authname"){
                name = $(this).val();
            }else
            if($(this).attr('name') == "category"){
                cat = $(this).val();
            }
        });

        var parent = div.parent().closest('div');
        parent.children('div .createContainer').remove();
        parent.append(showAuth(name, cat));
        parent.append(showCreateAuth());
    }
});

/**
 * showAuth(authName, authCat)
 * @param authName - Name of an Authentication Scheme
 * @param authCat - Category of an Authentication Scheme
 * @returns {string} - HTML-String to append a Div at the End of the Authentication Scheme List
 */
function showAuth(authName, authCat){
    var output = '';
    output += '<div class="authContainer">';
    output += '<img src="img/dash.png" class="remove-auth" />';
    output += '<div class="authName">';
    output += authName;
    output += '</div>';
    output += '<div class="authCat">';
    output += authCat;
    output += '</div>';
    output += '</div>';
    return output;
}

/**
 * showCreateAuth()
 * @returns {string} - HTML-String to append a Div to add a new Authentication Scheme
 */
function showCreateAuth(){
    var output = "";
    output += '<div class="createContainer">';
    output += '<img src="img/cross.png" class="add-auth" />';
    output += '<input type="text" class="add-auth" name="authname" value="New Authentication">';
    output += '<input type="texr" class="add-auth" name="category" value="Category">';
    output += '</div>';
    return output;
}
