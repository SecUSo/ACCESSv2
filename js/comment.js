/**
 * #####################################################################################################################
 * Copyright (C) 2016   Philip Stumpf
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
 * AJAX Requests for Commenting Function
 * -> Takes Input and sends to respective Controller to post comments, delete comments, enable or disable threads,
 *    post subthread comments
 **/

var classData = {};
$(document).ready(function () {



    $('.subfeature_info_box').popover({
        container:'body'
    });

    var sortedList = jQuery.makeArray($("#selectSubClass").find('option')).sort(function (a, b) {
        return (jQuery(a).text() > jQuery(b).text()) ? 1 : -1;
    });

    $("#selectSubClass").append(jQuery(sortedList)).attr('selectedIndex', 0);

    $("#selectSubClass").val($("#selectSubClass option:first").val());

    $("#selectSubClass").trigger('change');

    let reference_count = 0;

    var selected_subfeature_suggestion = "";

    //<content.php>
/* Timeline entries can't be deleted
    $(".timeline-close-btn").click(function (e) {
        var changelog_id = $(this).attr("id");

        $.ajax({
            type: "POST",
            url: "?AdminDeleteChangelogEntry",
            dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    id: changelog_id
                })
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("ajax error"); // show response from the php script.
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
    //</content.php>
*/

    $(".form-panelfooter").submit(function (e) {
        var form = $(this);

        $.ajax({
            type: "POST",
            url: "?AddDiscussion",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    threadId: form.find('#ThreadIDsubthread').val(),
                    contentType: form.find('#contentTypesubthread').val(),
                    contentId: form.find('#contentIDsubthread').val(),
                    commentType: 'subthread',
                    comment: form.find('#enterCommentsubthread').val()
                })
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("ajax error"); // show response from the php script.
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.

    });


    $(".form-comment").submit(function (e) {

        if ($('#selectType').val() == "3") {

            var subfeature = selected_subfeature_suggestion;
            var scheme = $("#contentID").val();
            if (subfeature == "" || scheme == "") {
                alert("Please select a subfeature!")
                e.preventDefault(); // avoid to execute the actual submit of the form.
                return;
            }
            var value = "";
            if ($('#' + selected_subfeature_suggestion).find("img").hasClass("in-auth"))
                value = "1";
            else
                value = "0";


            let references = "";
            let ref = document.getElementsByName("suggestion_reference[]");
            for (let i = 0; i < ref.length; i++){
                let item = processReference(ref[i]);
                references += item;
                references += '<br>';
            }


            var comment = $('#subfeature_suggestion_comment').val();

            $.ajax({
                type: "POST",
                url: "?MakeSubfeatureSuggestion",
                //dataType: 'json',
                data: {
                    "json_data": JSON.stringify({
                        scheme: scheme,
                        subfeature: subfeature,
                        references: references,
                        comment: comment,
                        value: value
                    })
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    alert("ajax error"); // show response from the php script.
                }
            });
        }
        else if ($('#selectType').val() == "4") {

            var feature_data = $("#selectSubClass option:selected");
            var feature = feature_data.attr("feature");
            var subclass = feature_data.attr("class");
            var class_values = {};
            var scheme = $("#contentID").val();
            $("#classification_table tbody").find("tr").each(function (index) {
                var auth_1 = $(this).find(".auth_1").eq(0).attr("value");
                var auth_2 = $(this).find(".auth_2").eq(0).attr("value");
                var value = $(this).find(".btn-primary").eq(0).attr("value");

                var temp = {};
                temp["auth_1"] = auth_1;
                temp["auth_2"] = auth_2;
                temp["value"] = value;
                class_values[index] = temp;
            });

            if (feature == "" || subclass == "" || class_values.length == 0) {
                alert("Error!")
                e.preventDefault(); // avoid to execute the actual submit of the form.
                return;
            }

            let references = "";
            let ref = document.getElementsByName("suggestion_reference[]");
            for (let i = 0; i < ref.length; i++){
                let item = processReference(ref[i]);
                references += item;
                references += '<br>';
            }

            var comment = $('#classification_suggestion_comment').val();

            $.ajax({
                type: "POST",
                url: "?MakeClassificationSuggestion",
                //dataType: 'json',
                data: {
                    "json_data": JSON.stringify({
                        feature: feature,
                        class: subclass,
                        references: references,
                        comment: comment,
                        classvalues: class_values,
                        scheme: scheme
                    })
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    alert("ajax error"); // show response from the php script.
                }
            });
        }
        else {

            var commentType = "";
            if ($('#selectType').val() == "1")
                commentType = "comment";

            $.ajax({
                type: "POST",
                url: "?AddDiscussion",
                //dataType: 'json',
                data: {
                    "json_data": JSON.stringify({
                        contentType: $('#contentType').val(),
                        contentId: $('#contentID').val(),
                        commentType: commentType,
                        comment: $('#enterComment').val()
                    })
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    alert("ajax error"); // show response from the php script.
                }
            });
        }

        e.preventDefault(); // avoid to execute the actual submit of the form.

    });


    $(".del_btn").click(function () {
        $.ajax({
            type: "POST",
            url: "?AdminDeleteDiscussion",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    contentType: $('#contentType').val(),
                    isSubthread: "0",
                    contentId: $(this).val()
                })
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("ajax error"); // show response from the php script.
            }
        });
    });

    $('.del_btn_subthread').click(function () {
        $.ajax({
            type: "POST",
            url: "?AdminDeleteDiscussion",
            data: {
                "json_data": JSON.stringify({
                    contentType: $('#contentType').val(),
                    isSubthread: "1",
                    contentId: $(this).val()
                })
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

    $('.close_btn').click(function () {
        $.ajax({
            type: "POST",
            url: "?AdminRejectSuggestionThread",
            data: {
                "json_data": JSON.stringify({
                    id: $(this).val(),
                    contentType: $('#contentType').val()
                })
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

    $('.accept_btn').click(function () {
        $.ajax({
            type: "POST",
            url: "?AdminAcceptSuggestionThread",
            data: {
                "json_data": JSON.stringify({
                    id: $(this).val(),
                    contentType: $('#contentType').val()
                })
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

    $('#addRefSub').on("click", function (e) {
        addHTMLforRef($("#reference_subfeature"));

    });

    $('#reference_subfeature').on("click", "#remove", function () {
        $(this).parent('div').remove();
    });

    $('#addRefClass').on("click", function (e) {
        addHTMLforRef($("#reference_classification"));

    });

    $('#reference_classification').on("click", "#remove", function () {
        $(this).parent('div').remove();
    });


    function processReference(div) {

        let fields = div.getElementsByClassName("input_flag");
        let array = [];
        for (let i = 0; i < fields.length; i++) {
        array.push(fields[i]);
        }
        let ref ="";

        for (let i = 0; i < fields.length; i++) {

            if(fields[i].value != "") {
                ref += fields[i].value;

                if ((i != fields.length - 1) && moreInputs(array.slice(i+1, fields.length)))
                    ref += ", ";
            }
        }
        return ref;
    }

    function moreInputs(fields){
        let x = false;
        for (let i = 0; i < fields.length; i++) {
            if(fields[i].value != "") {
                x = true;
                return x;
            }
        }
        return x;
    }

    function addHTMLforRef(div){

        let count = reference_count.toString();
        reference_count++;
        let data = '<div name="suggestion_reference[]" id="suggestion_ref'+count+'" style="border-bottom-style: solid; border-color: #dddddd"></br><label for="selectTypeRef'+count+'">Select Reference Type:</label>';
        data += '<select class="form-control reference" style="background-color: #eeeeee" id="selectTypeRef'+count+'">';
        data += '<option value="0"></option>';
        data += '<option value="1">Article</option>';
        data += '<option value="2">Book</option>';
        data += '<option value="3">Booklet</option>';
        data += '<option value="4">Conference</option>';
        data += '<option value="5">Inbook</option>';
        data += '<option value="6">Incollection</option>';
        data += '<option value="7">Inproceedings</option>';
        data += '<option value="8">Manual</option>';
        data += '<option value="9">Master Thesis</option>';
        data += '<option value="10">Miscellaneous</option>';
        data += '<option value="11">Ph.D. Thesis</option>';
        data += '<option value="12">Proceedings</option>';
        data += '<option value="13">Techreport</option>';
        data += '<option value="14">Unpublished</option></select>';
        data += '<label class="hidden refLabel'+count+'" for="reference_author" id="reference_authorLabel'+count+'">Author</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_author'+count+'"></textarea>';
        data += '<label  class="hidden refLabel'+count+'" for="reference_title" id="reference_titleLabel'+count+'">Title</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_title'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_journal" id="reference_journalLabel'+count+'">Journal</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_journal'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_year" id="reference_yearLabel'+count+'">Year</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_year'+count+'"></textarea>';
        data += '<label  class="hidden refLabel'+count+'" for="reference_author_or_editor" id="reference_author_or_editorLabel'+count+'">Author/Editor</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_author_or_editor'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_publisher" id="reference_publisherLabel'+count+'">Publisher</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_publisher'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_booktitle" id="reference_booktitleLabel'+count+'">Booktitle</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_booktitle'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_pages" id="reference_pagesLabel'+count+'">Pages/Chapter</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_pages'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_address" id="reference_addressLabel'+count+'">Address</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_address'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_school" id="reference_schoolLabel'+count+'">School</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_school'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_institution" id="reference_institutionLabel'+count+'">Institution</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_institution'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_note" id="reference_noteLabel'+count+'">Note</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_note'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_month" id="reference_monthLabel'+count+'">Month</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_month'+count+'"></textarea>';
        data += '<label class="hidden refLabel'+count+'" for="reference_howpub" id="reference_howpubLabel'+count+'">Form of Publication</label>';
        data += '<textarea class="form-control hidden ref'+count+'" rows="1" id="reference_howpub'+count+'"></textarea>';
        data += '<a type="button" class="btn btn-xs pull-right"  id="remove">Remove Reference</a><br/><br/></div>';

        $(div).append(data);
        addEventHandler('#suggestion_ref' + count, count);

    }

    function addEventHandler(div, counter) {
        $('.ref-div').on('change', div ,function () {

            $('.ref'+ counter).addClass('hidden').removeClass('input_flag');
            $('.refLabel'+ counter).addClass('hidden');

            if ($('#selectTypeRef'+ counter).val() == "1") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_journalLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_journal'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "2") {
                $('#reference_author_or_editorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_publisherLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author_or_editor'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_publisher'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "3") {
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');

            }
            else if ($('#selectTypeRef'+ counter).val() == "4") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_booktitleLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_booktitle'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "5") {
                $('#reference_author_or_editorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_pagesLabel'+ counter).removeClass('hidden');
                $('#reference_publisherLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author_or_editor'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_pages'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_publisher'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            }
            else if ($('#selectTypeRef'+ counter).val() == "6") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_booktitleLabel'+ counter).removeClass('hidden');
                $('#reference_publisherLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_booktitle'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_publisher'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');

            }
            else if ($('#selectTypeRef'+ counter).val() == "7") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_booktitleLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_booktitle'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "8") {
                $('#reference_addressLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_address'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "9") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_schoolLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_school'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "10") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_monthLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_howpubLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_month'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_howpub'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "11") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_schoolLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_school'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "12") {
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "13") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_institutionLabel'+ counter).removeClass('hidden');
                $('#reference_yearLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_institution'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_year'+ counter).removeClass('hidden').addClass('input_flag');


            } else if ($('#selectTypeRef'+ counter).val() == "14") {
                $('#reference_authorLabel'+ counter).removeClass('hidden');
                $('#reference_titleLabel'+ counter).removeClass('hidden');
                $('#reference_noteLabel'+ counter).removeClass('hidden');
                $('#reference_author'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_title'+ counter).removeClass('hidden').addClass('input_flag');
                $('#reference_note'+ counter).removeClass('hidden').addClass('input_flag');

            }
        });
    }



    function drawClassifcationTable(data) {

        var data_arr = $.parseJSON(data);

        var classData = data_arr["classdata"];
        var scheme_descriptions = data_arr["scheme_descriptions"];
        var auth_name = $(".auth_name").text();
        var keys = Object.keys(classData);

        var html_data = '<div class="table-responsive"><table id="classification_table" class="table"><tbody>';

        for (var i = 0; i < keys.length; i++) {
            var sub_keys = Object.keys(classData[keys[i]]);
            var auth1 = keys[i];
            for (var j = 0; j < sub_keys.length; j++) {
                var auth2 = sub_keys[j];
                var value = classData[keys[i]][sub_keys[j]];
                if (auth1 == auth_name) {
                    html_data += '<tr><td class="auth_1" style="text-align: center;vertical-align:middle;width:35%;" value="' + auth1 + '">' + auth1 + '</td>';

                    html_data += '<td id="value" style="text-align: center; vertical-align:middle;width:30%;"> \
                    <div class="btn-group">';

                    if (value == "1.5")
                        html_data += '<button type="button" value="1.5" class="btn btn-xs btn-primary threewaytoggle">is better than</button>';
                    else
                        html_data += '<button type="button" value="1.5" class="btn btn-xs btn-default threewaytoggle">is better than</button>';

                    if (value == "1")
                        html_data += '<button type="button" value="1" class="btn btn-xs btn-primary threewaytoggle">is equal to</button>';
                    else
                        html_data += '<button type="button" value="1" class="btn btn-xs btn-default threewaytoggle">is equal to</button>';

                    if (value == "0.666667")
                        html_data += '<button type="button" value="0.666667" class="btn btn-xs btn-primary threewaytoggle">is worse than</button>';
                    else
                        html_data += '<button type="button" value="0.666667" class="btn btn-xs btn-default threewaytoggle">is worse than</button>';


                    html_data += '</div></td>';

                    html_data += '<td class="auth_2" style="text-align: center; vertical-align:middle;width:35%;" value="' + auth2 + '">' +
                        '<span class="scheme_info_box" data-content="' + scheme_descriptions[auth2] + '" title="Description" rel="popover" data-placement="bottom" \
                     data-trigger="hover">' + auth2 + '</span></td></tr>';

                }
                else if (auth2 == auth_name) {

                    html_data += '<tr><td class="auth_2" style="text-align: center;vertical-align:middle;width:35%;" value="' + auth2 + '">' + auth2 + '</td>';

                    html_data += '<td id="value" style="text-align: center; vertical-align:middle;width:30%;"> \
                    <div class="btn-group">';

                    if (value == "0.666667")
                        html_data += '<button type="button" value="0.666667" class="btn btn-xs btn-primary threewaytoggle">is better than</button>';
                    else
                        html_data += '<button type="button" value="0.666667" class="btn btn-xs btn-default threewaytoggle">is better than</button>';

                    if (value == "1")
                        html_data += '<button type="button" value="1" class="btn btn-xs btn-primary threewaytoggle">is equal to</button>';
                    else
                        html_data += '<button type="button" value="1" class="btn btn-xs btn-default threewaytoggle">is equal to</button>';

                    if (value == "1.5")
                        html_data += '<button type="button" value="1.5" class="btn btn-xs btn-primary threewaytoggle">is worse than</button>';
                    else
                        html_data += '<button type="button" value="1.5" class="btn btn-xs btn-default threewaytoggle">is worse than</button>';


                    html_data += '</div></td>';

                    html_data += '<td class="auth_1" style="text-align: center; vertical-align:middle;width:35%;" value="' + auth1 + '">' +
                        '<span class="scheme_info_box" data-content="' + scheme_descriptions[auth1] + '" title="Description" rel="popover" data-placement="bottom" \
                     data-trigger="hover">' + auth1 + '</span></td></tr>';

                }
            }
        }

        html_data += "</tbody></table></div>";

        $('#siteloader').html(html_data);

        $('.scheme_info_box').popover();

        $(".threewaytoggle").click(function () {
            $(this).parent().find("button").each(function () {
                $(this).removeClass("btn-primary");
                $(this).addClass("btn-default")
            });

            $(this).removeClass("btn-default");
            $(this).addClass("btn-primary");
        });

    }

    $('#selectSubClass').change(function () {
        var selectedOption = $('#selectSubClass option:selected');
        $.ajax({
            type: "GET",
            url: "?GetClassDataForClassificationSuggestion&feature=" + selectedOption.attr("feature") + "&class=" + selectedOption.attr("class"),
            success: function (data) {
                drawClassifcationTable(data);
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

    $('#selectType').change(function () {
        if ($('#selectType').val() == "1" || $('#selectType').val() == "2") {
            $('#suggestion_plaintext').removeClass('hidden');
            $('#suggestion_ec').addClass('hidden');
            $('#suggestion_rate').addClass('hidden');
        }
        else if ($('#selectType').val() == "3") {
            addHTMLforRef($('#reference_subfeature'));
            $('#suggestion_plaintext').addClass('hidden');
            $('#suggestion_ec').removeClass('hidden');
            $('#suggestion_rate').addClass('hidden');
        }
        else if ($('#selectType').val() == "4") {
            addHTMLforRef($('#reference_classification'));
            $('#suggestion_plaintext').addClass('hidden');
            $('#suggestion_ec').addClass('hidden');
            $('#suggestion_rate').removeClass('hidden');
            var selectedOption = $('#selectSubClass option:selected');
            $.ajax({
                type: "GET",
                url: "?GetClassDataForClassificationSuggestion&feature=" + selectedOption.attr("feature") + "&class=" + selectedOption.attr("class"),
                success: function (data) {
                    drawClassifcationTable(data);
                },
                error: function () {
                    alert("ajax error");
                }
            });
        }
    });

    var lastSubfeature = "";


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


    $("#authsubfeature").on("click", "img", function (e) {
            if (selected_subfeature_suggestion == "") {
                if ($.inArray($(this).parent().parent().parent().parent().attr("id"), selectiveFeatures) >= 0) {
                    if ($(this).hasClass("in-auth"))
                        return;

                    $("#suggestion_ec_body").css("background", "#f5f5f5");
                    $(this).next("span").eq(0).css("color", "red");

                    selected_subfeature_suggestion = $(this).parent().attr('id');

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
                else if ($.inArray($(this).parent().parent().parent().parent().attr("id"), selectiveFeaturesWithNullclass) >= 0) {
                    if ($(this).hasClass("in-auth")) {
                        $(this).removeClass("in-auth");
                        $(this).addClass("not-in-auth");
                        $(this).attr('src', 'img/dash.png');
                        lastSubfeature = $(this);
                        $("#suggestion_ec_body").css("background", "#f5f5f5");
                        $(this).next("span").eq(0).css("color", "red");

                        selected_subfeature_suggestion = $(this).parent().attr('id');
                        return;
                    }
                    else {
                        $("#suggestion_ec_body").css("background", "#f5f5f5");
                        $(this).next("span").eq(0).css("color", "red");

                        selected_subfeature_suggestion = $(this).parent().attr('id');

                        $(this).parent().parent().parent().parent().find(".in-auth").each(function () {
                            $(this).removeClass("in-auth");
                            $(this).attr('src', 'img/dash.png');
                            $(this).addClass("not-in-auth");
                            lastSubfeature = $(this);
                        });

                        if ($(this).parent().parent().parent().parent().find(".in-auth").length == 0)
                            lastSubFeature = "none";

                        $(this).removeClass("not-in-auth");
                        $(this).addClass("in-auth");
                        $(this).attr('src', 'img/cross.png');
                        return;
                    }
                }

                $("#suggestion_ec_body").css("background", "#f5f5f5");
                $(this).next("span").eq(0).css("color", "red");

                selected_subfeature_suggestion = $(this).parent().attr('id');


                if ($(this).attr('class') == "in-auth") {
                    $(this).attr('src', 'img/dash.png');
                    $(this).attr('class', 'not-in-auth');
                } else if ($(this).attr('class') == "not-in-auth") {
                    $(this).attr('src', 'img/cross.png');
                    $(this).attr('class', 'in-auth');
                }
            }
            else if (selected_subfeature_suggestion == $(this).parent().attr('id')) {
                $("#suggestion_ec_body").css("background", "#ffffff");
                $(this).next("span").eq(0).css("color", "");

                selected_subfeature_suggestion = "";

                if ($.inArray($(this).parent().parent().parent().parent().attr("id"), selectiveFeatures) >= 0) {
                    $(this).removeClass("in-auth");
                    $(this).addClass("not-in-auth");
                    $(this).attr('src', 'img/dash.png');
                    lastSubfeature.removeClass("not-in-auth");
                    lastSubfeature.attr('src', 'img/cross.png');
                    lastSubfeature.addClass("in-auth");
                    lastSubfeature = "";
                    return;

                }
                else if ($.inArray($(this).parent().parent().parent().parent().attr("id"), selectiveFeaturesWithNullclass) >= 0) {
                    if (lastSubfeature == "none" || lastSubfeature == "") {
                        $(this).parent().parent().parent().parent().find(".in-auth").each(function () {
                            $(this).removeClass("in-auth");
                            $(this).attr('src', 'img/dash.png');
                            $(this).addClass("not-in-auth");
                            lastSubfeature = $(this);
                        });
                    }
                    else {
                        $(this).removeClass("in-auth");
                        $(this).addClass("not-in-auth");
                        $(this).attr('src', 'img/dash.png');
                        lastSubfeature.removeClass("not-in-auth");
                        lastSubfeature.attr('src', 'img/cross.png');
                        lastSubfeature.addClass("in-auth");
                        lastSubfeature = "";
                        return;
                    }
                }

                lastSubfeature = "";

                if ($(this).attr('class') == "in-auth") {
                    $(this).attr('src', 'img/dash.png');
                    $(this).attr('class', 'not-in-auth');
                } else if ($(this).attr('class') == "not-in-auth") {
                    $(this).attr('src', 'img/cross.png');
                    $(this).attr('class', 'in-auth');
                }
            }
        }
    );


});

