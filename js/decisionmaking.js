$(document).ready(function() {
    malkeElementsDroppable();
    toggleFolding();
    sendConfiguration();
    deselectCheckboxesOnClickOfClass0();
    overlayReady();
    deselectRadioButtons();
    setHelpBoxHook();
    var items = document.querySelectorAll(".fold_category");
    for (var i = 0; i < items.length; i++) {
            items[i].click();
    }
    var items = document.querySelectorAll(".fold_feature");
    for (var i = 0; i < items.length; i++) {
        items[i].click();
    }
});

function setHelpBoxHook(){
    var helpBox = $('#helpBox');

    $('.info-help').click(function(){
        var helpBoxText = "<h3>"+$(this).attr('id')+"</h3><p>"+$(this).attr('title')+"</p>";
        helpBox.html(helpBoxText);
        document.getElementById('helpBox').style.display = "block";
    });

    helpBox.click(function(){
        document.getElementById('helpBox').style.display = "none";
    });
}

function overlayReady() {
    $('#close').click(
        function() {
            $('#container').hide('slow',
                function() {
                    $('#overlay').fadeOut();
                }
            );
        }
    );
}


function toggleFolding() {

    $(".fold_category").on("click", function() {
        $(this).closest("div").children().not(".fold_category, .category_name").toggle("fold", {size:"50px"}, 500);
    });

    $(".fold_feature").on("click", function() {

        $(this).closest("div").children().not(".fold_feature, .feature_name, .select-feat").toggle("fold", {size:"50px"}, 500);
    });

}

function malkeElementsDroppable() {

    $(".category_conatiner_DP").sortable(
        {
            items: ".category_DP"
        }
    );

    $(".category_DP").sortable(
        {
            items: ".feature_DP"
            //cancel: ["input.select-subfeat", "span.subfeat_name"]
        }

    );

    $(".feature_DP").sortable(
        {
            items: ".subfeature_DP"
        }

    );

    $('.category_conatiner_DP').disableSelection();

}

function sendConfiguration() {

    $("#sendSelection").on("click", function() {
        //createOrderedArrayOfSubfeatures();
        createConfigurationObject();
        console.log("fire");
    });
}

function createConfigurationObject(){

    /**
     * Take all the children of type "div" of our container, these are the children of
     * type category. Call the according function for taking the configuration of the
     * Features/Subfeatures...
     */

    // create an array of strings containing the name of the subfeatre of a feature
    var myConfiguration = {};

    $("#category_container").children("div").each(function() {  // die children sind die Kategorien
        //console.log("#" + this.id);
        myConfiguration[this.id] = createOrderedArrayOfFeatures("#" + this.id);
    });

    var json = JSON.stringify(myConfiguration);
    //console.log(json);
    //console.log("READY");
    $.ajax(
        {
            method: "POST",
            url: "?DecisionEvaluation",
            cache: false,
            data:
            {
                "json":json
            }
        }
    ).done(function(html) {

        var data = html.split('#');

        var data_val = $.parseJSON(data[0]);
        var data_desc = $.parseJSON(data[1]);

        var resultTable = "<table><tr><th>Authentication Scheme</th><th>Performance</th></tr>";

        var i = 0;

        $.each(data_val, function(authName, performance) {
            if (i > 4) {
                return false;
            }
            resultTable += '<tr><td>' +
                '<span class="glyphicon glyphicon-question-sign info-help" id="'+authName+'" title="'+data_desc[authName]+'"></span>' +
                authName+"</td><td>"+performance+"</td></tr>";
            i++;
        } );

        resultTable += "</table>";

        $('#overlay').show('slow',
            function() {
                $('#container').fadeIn('slow');
                $('#changeText').html(resultTable);
            }
        );

        setHelpBoxHook();
        //console.log(html);
    }).fail(function(xhr, status, error) {
        console.error(xhr.responseText);
    }
    );

}

function createOrderedArrayOfFeatures(elementID) { // Übergebe Kategorie

    /**
     * Take the ID of a Feature and make an array of subfeatures, accordingly
     */
    var categoryFeatures = {};
    var categorySubset = {};

    $(elementID).children(".feature_DP").each(function() {
        categoryFeatures[this.id] = [];
        if ($("#"+this.id).children("[id^=IN_F_]").is(":checked")) {
            categoryFeatures[this.id].push(1);
        } else {
            categoryFeatures[this.id].push(0);
        }
        //categoryFeatures[this.id].push($("#"+this.id).children("[id^=IN_F_]").is(":checked"));
        categoryFeatures[this.id].push(createOrderedArrayOfSubfeatures("#"+this.id));
    });
    //console.log(categoryFeatures);
    //var json = JSON.stringify(categoryFeatures);
    return categoryFeatures;
}


function createOrderedArrayOfSubfeatures(elementID) { // elementID ist ein Feature
    //console.log($(elementID).attr("class"));
    /**
     * Declare an object we want to use for storage of subfeature names and values
     */
    var subFeatureArray = {};
    /**
     * Then:
     * 1. get all the children of our feature here and iterate over them
     * 2. using the id of the element, store it's value (i.e. if checked) during the iteration
     * 3. return the result to the caller
     */

    /**
     * Hier über die entsprechenden Kinder des aktuellen divs iterieren und direkt
     * die entsprechenden Werte speichern.
     */
    $(elementID).children(".subfeature_DP, fieldset").each(function() {
        //console.log($(this).children());
        //console.log($(this).children("[id^=IN_SF_]"));
        if($(this).prop("tagName") == "FIELDSET"){
            //console.log("inside fieldset");
            $(this).children("div").each(function() {
                if ($(this).children("[id^=IN_SF_]").is(":checked")) {
                    subFeatureArray[this.id] = 1;
                } else {
                    subFeatureArray[this.id] = 0;
                }
                //subFeatureArray[this.id] = $(this).children("[id^=IN_SF_]").is(":checked");
            });
        }else {
            if ($(this).children("[id^=IN_SF_]").is(":checked")) {
                subFeatureArray[this.id] = 1;
            } else {
                subFeatureArray[this.id] = 0;
            }
            //subFeatureArray[this.id] = $(this).children("[id^=IN_SF_]").is(":checked"); // attention: $(this).children can't be $('#'+this.id).children !!!
        }
    });

    return subFeatureArray;

}

function deselectCheckboxesOnClickOfClass0() {

    $(".selectability_2").on("click", "[id=IN_SF_0]", function() {

        if ($(this).is(':checked')) {
            $(this).closest("div").siblings().each(function() {
                $(this).find("input").prop("checked", false);
                $(this).find("input").attr("disabled", true);
            });
        } else {

            $(this).closest("div").siblings().each(function() {
                $(this).find("input").removeAttr("disabled");
            });

        }

    });

}


function deselectRadioButtons() {
    $("input[type=radio]").on("click", function() {
        //var ID = $(this).attr("id");
        //var id = '\''+ID+'\'';
        console.log($(this).attr('id'));
        if ($(this).attr('checked')) {
            $(this).attr('checked', false);
            //$(this).attr('previousValue', true);
        } else {

            $(this).attr('checked', true);
            $(this).attr('previousValue', false);
        }
    });
}








