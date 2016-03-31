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

$(document).ready(function () {

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
        $.ajax({
            type: "POST",
            url: "?AddDiscussion",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    contentType:$('#contentType').val(),
                    contentId:  $('#contentID').val(),
                    commentType: $('#selectType').val(),
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

        e.preventDefault(); // avoid to execute the actual submit of the form.

    });

    $(".del_btn").click(function(){
        $.ajax({
            type: "POST",
            url: "?AdminDeleteDiscussion",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    contentType:$('#contentType').val(),
                    isSubthread: "0",
                    contentId:$(this).val()
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

    $('.del_btn_subthread').click(function(){
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

    $('.close_btn').click(function(){
        $.ajax({
            type: "POST",
            url: "?AdminRejectSuggestionThread",
            data: {
                "json_data": JSON.stringify({
                    id:$(this).val(),
                    contentType: $('#contentType').val()
                })
            },
            success: function (data){
                location.reload();
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

    $('.accept_btn').click(function(){
        $.ajax({
            type: "POST",
            url: "?AdminAcceptSuggestionThread",
            data: {
                "json_data": JSON.stringify({
                    id:$(this).val(),
                    contentType: $('#contentType').val()
                })
            },
            success: function (data){
                location.reload();
            },
            error: function () {
                alert("ajax error");
            }
        });
    });

});

