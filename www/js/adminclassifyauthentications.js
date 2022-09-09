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
 * Set related fields to reciprocal values of the Drop-Down Fields
 */
$(document).ready(function () {
    $.each($('select.dropdown'), function () {
        var correspondingField = $(this).attr('id');
        var coord = correspondingField.split('_');
        var val = eval($('#' + correspondingField + ' option:selected').val());

        if (val > 1) {
            $('td#' + String(coord[1] + '_' + String(coord[0]))).html('2/3');
        } else if (val < 1) {
            $('td#' + String(coord[1] + '_' + String(coord[0]))).html('3/2');
        } else {
            $('td#' + String(coord[1] + '_' + String(coord[0]))).html('1');
        }
    });

    //fixed table header left
    $("#classification-table").tableHeadFixer({'left': 1});
    $("#classification-table").tableHeadFixer({'head' : true});
});

/**
 * Set OnChange-Listener to the DropDowns to set the reciprocal value to the related field
 */
$('select.dropdown').on('change', function () {
    var correspondingField = $(this).attr('id');
    var coord = correspondingField.split('_');
    var val = eval($('#' + correspondingField + ' option:selected').val());

    if (val > 1) {
        $('td#' + String(coord[1] + '_' + String(coord[0]))).html('2/3');
    } else if (val < 1) {
        $('td#' + String(coord[1] + '_' + String(coord[0]))).html('3/2');
    } else {
        $('td#' + String(coord[1] + '_' + String(coord[0]))).html('1');
    }

});

/**
 * Set OnClick-Event to the Send Scale Values Button
 * @var output : Array containing the Scale-Vales
 *  {
 *      "Class-Name":{
 *                      "Class-Name":Scale-Value,
 *                      "Class-Name":Scale-Value,
 *                      ...
 *                   },
 *      "Class-Name":{...},
 *      ...
 *  }
 */
$('#send_scales').on('click', function () {
    var output = {};
    var featureName = $('span#featureName').html();
    var className = $('span#className').html();
    var tableDimensions = parseInt($('tr :last-child').last().attr('id').split('_')[1]);

    for (var y = 0; y < tableDimensions; y++) {
        var rowName = $('#side_' + y).attr('name');

        output[rowName] = {};
        for (var x = y + 1; x <= tableDimensions; x++) {
            var colName = $('#head_' + x).attr('name');

            var toPush = String(eval($('select#' + String(y) + '_' + String(x) + ' option:selected').val()));
            output[rowName][colName] = toPush;
        }
    }

    var json = JSON.stringify(output);
    // Ajax Request to set the changes - Return to the Overview page
    $.ajax({
        method: 'POST',
        url: "?AdminEditClassifications",
        cache: false,
        data: {'feature': featureName, 'class': className, 'json': json}
    }).done(function (html) {
        console.log(html);
        window.location.href = "?AdminClassifyAuthenticationsFeatureOverview"
    }).fail(function (xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
    });

});