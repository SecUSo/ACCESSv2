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
 * AJAX Requests for User Edit Data Page
 * -> Takes Input and sends to respective Controller to change UserData
 **/

$(document).ready(function () {

    $(".alert").hide(); // hide alerts on form send, to correctly display new alerts
    $(".form-edituser").submit(function (e) {
        $(".alert").hide(); // hide alerts on form send, to correctly display new alerts
        $.ajax({
            type: "POST",
            url: "?EditUser",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    Id: $('#Id').val(),
                    Title: $('#Title').val(),
                    FirstName: $('#FirstName').val(),
                    LastName: $('#LastName').val(),
                    EMail: $('#EMail').val(),
                    Organization: $('#Organization').val(),
                    Password: $('#Password').val(),
                })
            },
            success: function (data) {
                console.log(data);
                var json_obj = $.parseJSON(data);
                if (json_obj.status == 0) {
                    window.location.href = "?Index";
                }
                else {
                    $("#error1").show();
                }
            },
            error: function () {
                alert("login ajax error"); // show response from the php script.
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.

    });

})
;

