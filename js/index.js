/*
 * MAKE TABLE ROWS CLICKABLE FOR LINKING TO CONTENT PAGES
 */

var auth_suggestion = {
    name: "",
    description: "",
    category: "",
    features: [],
    subfeatures: [],
    classvalues: []
};

jQuery(document).ready(function ($) {

    //hover effect
    $('.scheme_info_box').popover();

    $(".clickable-row").click(function () {
        window.document.location = $(this).data("href");
    });

    //scheme suggestion - enable category input for custom entries
    $("#formAddNewAuth_Category_Preselection").change(function () {
        if ($("#formAddNewAuth_Category_Preselection option:selected").text() == "New category") {
            $("#formAddNewAuth_Category").prop('disabled', false);
        }
        else {
            $("#formAddNewAuth_Category").prop('disabled', true);
        }
    });

    //delete
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#scroll_up_button').fadeIn();
        } else {
            $('#scroll_up_button').fadeOut();
        }
    });

    $('#scroll_up_button').click(function () {
        $('#scroll_up_button').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    $('#back-to-top').tooltip('show');

    var selectiveFeaturesWithNullclass = [
        "Scalable-for-Users",
        "Browser-Compatible",
        "Easy-to-Learn",
        "Easy-Recovery-from-Loss",
        "Negligible-Cost-per-User",
        "Server-Compatible",
        "Non-Proprietary",
        "Resilient-to-Targeted-Impersonation",
        "Resilient-to-Throttled-Guessing",
        "Resilient-to-Unthrottled-Guessing",
        "Resilient-to-Leaks-form-Other-Verifiers",
        "Resilient-to-Phishing",
        "Resilient-to-Theft",
        "Resilient-to-Third-Party",
        "Requiring-Explicit-Consent",
        "Unlinkable"];

    var selectiveFeatures = [
        "Memorywise-Effortless",
        "Nothing-to-Carry",
        "Physically-Effortless"];

    var additiveFeatures = [
        "Efficient-to-Use",
        "Infrequent-Errors",
        "Accessible",
        "Mature",
        "Resilient-to-Physical-Oberservation",
        "Resilient-to-Internal-Observation"];

    //subfeature suggestion effects
    $("#authsubfeature").on("click", "img", function (e) {

        if ($.inArray($(this).parent().parent().parent().parent().attr("id"), selectiveFeaturesWithNullclass) >= 0) {
            if ($(this).hasClass("in-auth")) {
                $(this).removeClass("in-auth");
                $(this).addClass("not-in-auth");
                $(this).attr('src', 'img/dash.png');
                return;
            }

            $(this).parent().parent().parent().parent().find(".in-auth").each(function () {
                $(this).removeClass("in-auth");
                $(this).attr('src', 'img/dash.png');
                $(this).addClass("not-in-auth");
                lastSubfeature = $(this);
            });

            $(this).removeClass("not-in-auth");
            $(this).addClass("in-auth");
            $(this).attr('src', 'img/cross.png');
            return;
        } else if ($.inArray($(this).parent().parent().parent().parent().attr("id"), selectiveFeatures) >= 0) {
            if ($(this).hasClass("in-auth"))
                return;

            $(this).parent().parent().parent().parent().find(".in-auth").each(function () {
                $(this).removeClass("in-auth");
                $(this).attr('src', 'img/dash.png');
                $(this).addClass("not-in-auth");
                lastSubfeature = $(this);
            });

            $(this).removeClass("not-in-auth");
            $(this).addClass("in-auth");
            $(this).attr('src', 'img/cross.png');
            return;
        }
        else if ($.inArray($(this).parent().parent().parent().parent().attr("id"), additiveFeatures) >= 0) {
            if ($(this).attr('class') == "in-auth") {
                $(this).attr('src', 'img/dash.png');
                $(this).attr('class', 'not-in-auth');
            } else if ($(this).attr('class') == "not-in-auth") {
                $(this).attr('src', 'img/cross.png');
                $(this).attr('class', 'in-auth');
            }
        }
    });

    //scheme suggestion bottom button copies
    $("#proceed0_bottom").click(function () {
        $("#proceed0").trigger("click");
    });

    $("#proceed1_bottom").click(function () {
        $("#proceed1").trigger("click");
    });

    $("#proceed1_copy_bottom").click(function () {
        $("#proceed1_copy").trigger("click");
    });

    $("#proceed2_bottom").click(function () {
        $("#proceed2").trigger("click");
    });

    $("#proceed3_bottom").click(function () {
        $("#proceed3").trigger("click");
    });

    //scheme suggestion: step 0 -> step 1
    $("#proceed0").click(function (event) {
        event.preventDefault();

        $("#addNewAuthPanel2").addClass("hidden")
        $("#addNewAuthPanel1").removeClass("hidden")
    });

    //scheme suggestion: step 1 -> step 2
    $("#proceed1").click(function (event) {
        event.preventDefault();

        if ($("#formAddNewAuth_Name").val() == "" ||
            $("#formAddNewAuth_Description").val() == "" ||
            $("#formAddNewAuth_Category_Preselection").val() == "" ||
            ($("#formAddNewAuth_Category_Preselection").val() == "New category" && $("#formAddNewAuth_Category").val() == "" ) ||
            ($("#formAddNewAuth_Category_Preselection").val() != "New category" && $("#formAddNewAuth_Category").val() != "" )) {
            alert("Please fill out all required fields correctly.")
            return;
        }

        auth_suggestion.name = $("#formAddNewAuth_Name").val().charAt(0).toUpperCase() + $("#formAddNewAuth_Name").val().slice(1);
        auth_suggestion.description = $("#formAddNewAuth_Description").val();
        if ($("#formAddNewAuth_Category_Preselection").val() == "New category")
            auth_suggestion.category = $("#formAddNewAuth_Category").val();
        else
            auth_suggestion.category = $("#formAddNewAuth_Category_Preselection").val();

        $("#addNewAuthPanel1").addClass("hidden");
        $("#addNewAuthPanel3").addClass("hidden");
        $("#addNewAuthPanel2").removeClass("hidden");

    });

    //scheme suggestion: step 2 -> step 3
    $("#proceed2").click(function () {
        //collect all features and active subfeatures from the table -> store in global struct (auth_suggestions.features)
        var features = [];
        var features_orig = [];
        $('.feature').each(function () {
            var subfeatures = [];
            $('.subfeature', this).each(function (index) {
                if ($('img', this).attr('class') == "in-auth") {
                    subfeatures.push($(this).attr('id'));
                    var temp = {};
                    temp[($(this).attr('id'))] = 1;
                    features_orig.push(temp);
                }
                else {
                    var temp = {};
                    temp[($(this).attr('id'))] = 0;
                    features_orig.push(temp);
                }
            });
            features.push(
                {
                    "feature": $(this).attr('id'),
                    "subfeatures": subfeatures
                });
        });

        auth_suggestion.features = features;
        auth_suggestion.subfeatures = features_orig;

        $.ajax({
            type: "POST",
            url: "?GetSubclassesForNewAuthSuggestion",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify(auth_suggestion.features)
            },
            success: function (data) {
                var temp = $.parseJSON(data);
                auth_suggestion.classvalues = temp["classdata"];
                var descriptions = temp["descriptions"];

                //build panels for classification
                var htmlContent = "";
                for (var i = 0; i < auth_suggestion.classvalues.length; i++) {
                    if (!auth_suggestion.classvalues[i].hasOwnProperty("data")) continue;

                    htmlContent += '<div class="panel panel-default classification-table" feature="' + auth_suggestion.classvalues[i].feature + '" subclass="';

                    if (auth_suggestion.classvalues[i].zeroclass == "1")
                        htmlContent += '0';
                    else
                        htmlContent += auth_suggestion.classvalues[i].class;

                    htmlContent += '"> \
                        <div id="classification_table_' + i + '" class="panel-heading classification-panel" data-toggle="collapse" \
                    data-target="#collapse_panel_' + i + '"> \
                        <h4 class="panel-title"> \
                        <b>Feature:</b> ' + auth_suggestion.classvalues[i].feature + ' <b>Class:</b> ' + auth_suggestion.classvalues[i].class + ' \
                    </h4> \
                    <span class="pull-right chevron-fix"><i class="glyphicon glyphicon-chevron-down"></i></span> \
                        </div> \
                        <div id="collapse_panel_' + i + '" class="panel-collapse collapse"> \
                        <table id="classvalues" class="table"> \
                    <tbody> \
                    <div id="collapse_panel_' + i + '" class="panel-collapse collapse">';

                    var keys = Object.keys(auth_suggestion.classvalues[i].data);
                    for (var j = 0; j < keys.length; j++) {
                        htmlContent += '<tr> \
                        <td id="auth_1" style="text-align: center;vertical-align:middle;width:35%;">\
                        <span class="scheme_info_box" data-content="' + auth_suggestion.description + '" title="Description" rel="popover" data-placement="bottom" \
                        data-trigger="hover">' + auth_suggestion.name + '</span></td> \
                        <td id="value" style="text-align: center; vertical-align:middle;width:30%;"> \
                        <div class="btn-group"> \
                        <button type="button" value="3/2" class="btn btn-xs btn-default threewaytoggle">is better than</button> \
                        <button type="button" value="1" class="btn btn-xs btn-primary threewaytoggle">is equal to</button> \
                        <button type="button" value="2/3" class="btn btn-xs btn-default threewaytoggle">is worse than</button> \
                        </div> \
                        </td> \
                        <td id="auth_2" value="' + Object.keys(auth_suggestion.classvalues[i].data)[j] + '" style="text-align: center; vertical-align:middle;width:35%;">\
                        <span class="scheme_info_box" data-content="' + descriptions[(Object.keys(auth_suggestion.classvalues[i].data)[j])] + '" title="Description" rel="popover" data-placement="bottom" \
                        data-trigger="hover">' + Object.keys(auth_suggestion.classvalues[i].data)[j] + '</span></td> \
                        </tr>';
                    }

                    htmlContent += '</div> \
                        </tbody> \
                        </table> \
                        <ul class="pager">';

                    if (i == 0) {
                        htmlContent += '<li class="next_table"><a href="#classification_table_' + (i + 1).toString() + '">Open Next Table →</a></li>';
                    }
                    else if ((i + 1) == auth_suggestion.classvalues.length) {
                        htmlContent += '<li class="prev_table"><a href="#classification_table_' + (i - 1).toString() + '">← Open Previous Table</a></li>';
                    }
                    else {
                        htmlContent += '<li class="prev_table" style="margin-right:50px;"><a href="#classification_table_' + (i - 1).toString() + '">← Open Previous Table</a></li> \
                        <li class="next_table" style="margin-left:50px;"><a href="#classification_table_' + (i + 1).toString() + '">Open Next Table →</a></li>';
                    }
                    htmlContent += '</ul> \
                        </div> \
                        </div> \
                        </div>';

                }

                htmlContent += '</br>';

                $('#classification_placeholder').html(htmlContent);
                $("#addNewAuthPanel2").addClass("hidden");
                $("#addNewAuthPanel3").removeClass("hidden");

                $('.scheme_info_box').popover();

                $(".next_table").click(function () {
                    $(this).parent().parent().parent().find("div").eq(0).trigger("click");
                    if ($(this).parent().parent().parent().next("div").find("div").eq(0).attr("aria-expanded") != "true")
                        $(this).parent().parent().parent().next("div").find("div").eq(0).trigger("click");
                });

                $(".prev_table").click(function () {
                    $(this).parent().parent().parent().find("div").eq(0).trigger("click");
                    if ($(this).parent().parent().parent().prev("div").find("div").eq(0).attr("aria-expanded") != "true")
                        $(this).parent().parent().parent().prev("div").find("div").eq(0).trigger("click");
                });

                $(".threewaytoggle").click(function () {
                    $(this).parent().find("button").each(function () {
                        $(this).removeClass("btn-primary");
                        $(this).addClass("btn-default")
                    });

                    $(this).removeClass("btn-default");
                    $(this).addClass("btn-primary");
                });

                $(".classification-panel").click(function () {
                    var nextDiv = ($(this).next("div"));
                    var glyphicon = ($(this).find("i"));

                    if (nextDiv.attr("aria-expanded") == "true") {
                        glyphicon.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                    } else {
                        glyphicon.removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                    }
                });

                $(".classification-panel").eq(0).trigger("click");

                $("#proceed1_copy").click(function (event) {
                    if (!confirm("Going back to the subfeature selection will result in a loss of all classification data. Go back?")) {
                        event.preventDefault();
                    }
                    else {
                        $("#addNewAuthPanel3").addClass("hidden");
                        $("#addNewAuthPanel2").removeClass("hidden");
                    }
                });
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

    //scheme suggestion: step 3 -> step 4 (final)
    $("#proceed3").click(function (event) {
        //get data from tables, ignore new (non-existing) classes (default value will be written when creating new class in backend
        $(".classification-table").each(function (index) {
            var subclass = $(this).attr("subclass");
            var tempArr = {};
            $(this).find("tr").each(function (index) {
                var auth_2 = $(this).find("#auth_2").attr("value");
                var value = $(this).find(".btn-primary").eq(0).attr("value");
                tempArr[auth_2] = value;
            });
            auth_suggestion.classvalues[index].data = {};
            auth_suggestion.classvalues[index].data[(auth_suggestion.name)] = tempArr;
            auth_suggestion.classvalues[index].class = subclass;
        });

        $.ajax({
            type: "POST",
            url: "?MakeAuthSuggestion",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify(auth_suggestion)
            },
            success: function (data) {
                $("#addNewAuthPanel3").addClass("hidden")
                $("#addNewAuthPanel4").removeClass("hidden")

            },
            error: function () {
                alert("ajax error");
            }
        });
    });

});


