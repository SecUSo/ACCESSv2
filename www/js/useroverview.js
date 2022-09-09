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
 * AJAX Requests for Admin User Overview Page
 * -> Takes Input and sends to respective Controller to
 *          Delete, Disable or Enable User
 **/


$(document).ready(function () {
    $(".btn-delete").click(function() {
        var tempthis = $(this);
        var c = confirm("Do you want do delete the selected User?");

        if (c) {

            $.ajax({
                type: "POST",
                url: "?AdminDeleteUser",
                //dataType: 'json',
                data: {
                    "json_data": JSON.stringify({
                        id: $(this).attr('userID')
                    })
                },
                success: function (data) {
                    tempthis.closest ('tr').remove ();
                },
                error: function () {
                    alert("delete ajax error"); // show response from the php script.
                }
            });
        }
    });

    /*
     * return code
     * -1 - user disabled (success)
     * 0 - user activated (success)
     * 1 - wrong input
     * 2 - user not found
     * format: { "status": code }
     */


    $(".btn-toggle").click(function() {
        var tempthis = $(this);
        $.ajax({
            type: "POST",
            url: "?AdminToggleUserStatus",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    id: $(this).attr('userID')
                })
            },
            success: function (data) {
                var json_obj = $.parseJSON(data);
                if (json_obj.status == 0) {
                    tempthis.html('Disable');
                    tempthis.addClass('btn-warning').removeClass('btn-success');
                }
                else if (json_obj.status == -1) {
                    tempthis.html('Enable');
                    tempthis.addClass('btn-success').removeClass('btn-warning');
                }
                else {
                    alert("thomas ist dumm"); // show response from the php script.
                }
            },
            error: function () {
                alert("login ajax error"); // show response from the php script.
            }
        });
    });
})
;

