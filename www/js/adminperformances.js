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
 * Set Click Listener to the Button and send the made Changes to the Backend
 * Also return to the Overview-Page
 */
$('#save-performances').on("click", "button", function(){
    var feature = $('#feature').text();
    var json = {};

    $('#performances-row').find('td').each(function(){
        json[$(this).attr('id')] = $(this).text();
    });

    delete json[undefined];

    var data = JSON.stringify(json);

    $.ajax({
        method: 'POST',
        url: "?AdminSavePerformances",
        cache: false,
        data: {'feature':feature,'json':data}
    }).done(function(html){
        console.log(html);
        window.location.href="?AdminPerformancesOverview"
    }).fail(function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
    });
});