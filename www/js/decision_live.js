/**
 * #####################################################################################################################
 * Copyright (C) 2017   Philip Stumpf
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
 * Scripts for Returning live information to User based on Input
 */
$( document ).ready(function() {
    getCount();
});


// Displays how many Authentications fulfill Hard-Constraints -> Toggles Alternate Decision Making Process every Time
// HardConstraints change

$(".subfeature").click(function(){
    getCount();
});

function getCount() {
    var subFeatureArray = {};
    var subFeatureOr = {};
    var compareArray = {};

    $("#subFeature-list> li").each(function() {
        subFeatureArray[this.id] = [];
        compareArray[this.id] = [];
        subFeatureArray[this.id].push(1);
        compareArray[this.id].push(1);
        var subFeatures = {};
        var compareSubFeatures = {};
        $(".subfeature", this).each(function() {
            if($(this).hasClass('and')) {
                subFeatures[this.id] = 1;
                compareSubFeatures[this.id] = 0;
            } else {
                subFeatures[this.id] = 0;
                compareSubFeatures[this.id] = 0;
                if($(this).hasClass('or')) {
                    var pairs = {};
                    var itemcounter = 0;
                    var subID = $(this).id;
                    $(this).find(".dropdown-toggle").each((function() {
                        var selText = $(this).text();
                        pairs[selText] = [];
                        pairs[selText].push(1);
                        itemcounter++;
                    }));
                    subFeatureOr[this.id] = [];
                    subFeatureOr[this.id].push(pairs);
                }
            }
        });
        subFeatureArray[this.id].push(subFeatures);
        compareArray[this.id].push(compareSubFeatures);
    });

    var subfeatures = JSON.stringify(subFeatureArray);
    var subfeaturesor = JSON.stringify(subFeatureOr);
    var compares = JSON.stringify(compareArray);

    $.ajax(
        {
            method: "POST",
            url: "?DecisionFeedback",
            cache: false,
            data:
                {
                    "subfeatures":subfeatures,
                    "subfeaturesor":subfeaturesor,
                    "compares":compares
                }
        }
    ).done(function(html) {

        var data = html.split('#');
        var data_val = $.parseJSON(data[0]);
        var data_max = $.parseJSON(data[1]);

        //

        $( function() {
                $('#counter-value').html(data_val);
                $('#counter-max').html(data_max);
            }
        );


    }).fail(function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    );
};