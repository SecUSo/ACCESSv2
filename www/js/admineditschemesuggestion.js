$(document).ready(function () {


    $(".classification-panel").click(function () {

        var nextDiv = ($(this).next("div"));
        var glyphicon = ($(this).find("i"));

        if (nextDiv.attr("aria-expanded") == "true") {
            glyphicon.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            glyphicon.removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');

        }
    });

    $(".submit_classvalues").click(function () {

        var edit_scheme_suggestion = {
            id: "",
            name: "",
            description: "",
            category: "",
            classvalues: [],
        };

        edit_scheme_suggestion.id = $("#id").val();
        edit_scheme_suggestion.name = $("#name").val();
        edit_scheme_suggestion.description = $("#description").val();
        edit_scheme_suggestion.category = $("#category").val();

        var tempArr = [];

        $('#classvalues tbody tr').each(function () {
            var arr = {};
            arr["cat_feature"] = $(this).find('td').eq(0).attr("cat_feature");
            arr["cat_class_feature"] = $(this).find('td').eq(1).attr("cat_class_feature");
            arr["auth_authentication_1"] = $(this).find('td').eq(2).attr("auth_authentication_1");
            arr["auth_authentication_2"] = $(this).find('td').eq(3).attr("auth_authentication_2");
            var value = $(this).find('td').eq(4).find("select :selected").attr("value");
            if (value == "3/2")
                arr["value"] = 1.5;
            else if (value == "1")
                arr["value"] = 1;
            else if (value == "2/3")
                arr["value"] = 0.666667;

            tempArr.push(arr);

        });

        edit_scheme_suggestion.classvalues = tempArr;

        $.ajax({
            type: "POST",
            url: "?AdminEditSchemeSuggestionSubmit",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify(edit_scheme_suggestion)
            },
            success: function (data) {
                window.location = "?AdminSuggestionOverview"

            },
            error: function () {
                alert("ajax error");
            }
        });

    });


});


