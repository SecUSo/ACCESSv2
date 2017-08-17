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
 * Base Javascript Class for Decision Making Platform
 * Starts Decision Making and Returns Data in Tables and Graphics
 */

$(".showResult").on("click", function() {
    // Initialize Variables
    var featureArray = {};
    var subFeatureArray = {};
    var subFeatureOr = {};
    var compareArray = {};
    var counter = 0;

    // Push Selected Features in List -> Same Value for Features in same bubble
    $(".feature-list").each(function() {
        var itemcounter = 0;
        $(this).find( "li" ).each(function() {
            featureArray[this.id] = [];
            featureArray[this.id].push(counter);
            itemcounter++;
        });
        if (itemcounter > 0) {
            counter++;
        }
        });

    // Create List of Features and Subfeatures for Hard Constraints. Value ID=1 if Subfeature is in "and" Selection.
    // Create Second List "or" with all "or"-Selection Pairs.
    // Create Second List Compares without Selection to get a Evaluation Result with no filtered Authentication Systems for Comparison in Evaluation.

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

    // Create JSON Arrays for Ajax Call
    var features = JSON.stringify(featureArray);
    var subfeatures = JSON.stringify(subFeatureArray);
    var subfeaturesor = JSON.stringify(subFeatureOr);
    var compares = JSON.stringify(compareArray);

    // Start Ajax Call to Server where Decision Request is calculated
    $.ajax(
        {
            method: "POST",
            url: "?DecisionEvaluation",
            cache: false,
            data:
                {
                    "features":features,
                    "subfeatures":subfeatures,
                    "subfeaturesor":subfeaturesor,
                    "compares":compares
                }
        }
    ).done(function(html) {

        // Get Result Data
        var data = html.split('#');
        //  Performances
        var data_val = $.parseJSON(data[0]);
        // Descriptions of Features
        var data_desc = $.parseJSON(data[1]);
        // Perfomances for Each Feature of Authentication Systems for Graph
        var data_perf = $.parseJSON(data[2]);
        // Non Filtered Perfomances for Comparison
        var data_compare = $.parseJSON(data[3]);
        // FailureList
        var data_fails = $.parseJSON(data[4]);


        // Create Result Table
        var resultTable = "";
        var compaererD = "";
        var i = 0;

        $.each(data_val, function(authName, performance) {
            if (i > 4) {
                resultTable += '<tr class="table-item table-item-hidden hidden"><td>' +
                    authName+'</td><td>'+performance+'</td><td><a href="?Content&id=' + data_desc[authName] + '" target="blank" style="font-size:22px">' +
                    '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td></tr>';
            } else {
                resultTable += '<tr class="table-item"><td>' +
                    authName+'</td><td>'+performance+'</td><td><a href="?Content&id=' + data_desc[authName] + '" target="blank" style="font-size:22px">' +
                    '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td></tr>';
            }
            i++;
        } );

        resultTable += '<tr id="compare-item" class="hidden table-item"></tr>';

        // Last Table Entry for Comparison with other Autentication Systems
        $.each(data_perf, function(authName) {
                if (authName in data_val) {
                    compaererD += '<li><a class="compare_link">' + authName + '</a></li>';
                } else {
                    compaererD += '<li class="failed_compare"><a class="compare_link">' + authName + '<span class="glyphicon glyphicon-flash" aria-hidden="true"></span></a></li>';
                }
            i++;
        } );


        $("#changeTable .table-item").remove();
        $('#changeTable tr:first-child').after(resultTable);
        $('#compare-dropdown li:first-child').after(compaererD);



        var keys = [];
        for (var key in featureArray) {
                keys.push(key);
        }

        $('#myChart').remove(); // this is my <canvas> element
        $('#changeChart').append('<canvas id="myChart"><canvas>');

        // Generate Graph
        var mDatasets = [];
        var colors= ['#5DA5DA', '#FAA43A', '#60BD68', '#F17CB0', '#B2912F', '#B276B2',
            '#DECF3F'];
        var color_count = 0;
        var count = 0;
            for (var authenticationNameKey in data_val) {
            if (count < 5) {
                var values = [];
                for (var featureNameKey in featureArray) {
                        values.push(data_perf[authenticationNameKey][featureNameKey]);
                }
                var datasetObject = {};
                    datasetObject['label'] = authenticationNameKey;
                    datasetObject['data'] = values;
                    datasetObject['backgroundColor'] = colors[color_count];
                 color_count++;


                mDatasets.push(datasetObject);
                count++;
                }
            }

        var ctx = document.getElementById('myChart').getContext('2d');

        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: keys,
                datasets: mDatasets

            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            //maxRotation: 90,
                            //minRotation: 90
                        }
                    }]
                }
            }
        });


        // Create Table for Evaluation of Authentications that failed Hard Constraints
        var resultDisclaimer = '<tr  class="table-item"><td colspan=\"3\" style=\"text-align: center\">No Authentications filtered by Hard Constraint(s). Please choose Hard Constraints in Step 2</td></tr>';
        var resultTable2 = "";
        var isempty = false;
        $.each(data_perf, function(authName2) {
            if (!(authName2 in data_val)) {
                isempty = true;
                resultTable2 += '<tr class="table-item"><td>' +  authName2 + '</td><td>' + data_fails[authName2] + '</td><td><a href="?Content&id=' + data_desc[authName2] + '" target="blank" style="font-size:22px"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td></tr>';
            }
            i++;
        } );

        if (isempty == true) {
            $('#changeTable2 tr:first-child').after(resultTable2);
        } else {
            $('#changeTable2 tr:first-child').after(resultDisclaimer);
        }



        // Show Result
        $("#feature-selection").hide();
        $("#subFeature-selection").hide();
        $("#decisionResult").show();



        var compareClicked = false;



        // Compare other Authentications in Result Table
        $("#compare-dropdown li").click(function(){
            if (compareClicked == true) {
                mDatasets.pop();
            }
            var tableRow = "";
            tableRow += '<tr id="compare-item" style="color: #F15854;"><td>' +$(this).text()+
                '</td><td >'+data_compare[$(this).text()]+'</td><td><a href="/" target="blank" style="font-size:22px">' +
                '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td></tr>';


            $('#compare_name').html($(this).text());
            $('#compare_perf').html(data_compare[$(this).text()]);
            $("#compare_perf").removeClass("hidden");
            $("#compare_info").removeClass("hidden");
            $("#compare_info").attr("href", '?Content&id=' + data_desc[$(this).text()]);

            $("#myChart").remove();
            $("#changeChart").append( '<canvas id="myChart"></canvas>' );
            var values = [];

            for (var featureNameKey in featureArray) {
                values.push(data_perf[$(this).text()][featureNameKey]);
            }
            var datasetObject = {};
            datasetObject['label'] = $(this).text(),
                datasetObject['data'] = values,
                datasetObject['backgroundColor'] = '#F15854'


            mDatasets.push(datasetObject);



            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: keys,
                    datasets: mDatasets

                },
                options: {
                    scales: {
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                //maxRotation: 90,
                                //minRotation: 90
                            }
                        }]
                    }
                }
            });

            compareClicked = true;

        });



        // Ajax Error Handling
    }).fail(function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    );
});



