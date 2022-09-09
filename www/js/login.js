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
 * AJAX Requests for Login
 * -> Takes Input and sends it to Login Controller to Start Logged in User Session
 **/

$(document).ready(function () {
    $(".alert").hide(); // HIDE ALERTS if JS LOADED

    /* AJAX CALL FOR login - SEND DATA TO BACKEND AND REGISTER USER
     * ON SUCCESS: LOG IN AND LINK TO LANDING PAGE
     * ON FAILURE: SHOW ERROR BASED ON RETURN CODE
     *
     * return code:
     * 0 - success
     * 1 - wrong input
     * 2 - user not found
     * 3 - wrong password
     * format: { "status": code }
     */


    $(".form-signin").submit(function (e) {
        $(".alert").hide(); // hide alerts on form send, to correctly display new alerts
        $.ajax({
            type: "POST",
            url: "?UserLogin",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    EMail: $('#inputEmail').val(),
                    Password: $('#inputPassword').val()
                })
            },
            success: function (data) {
                console.log(data);
                var json_obj = $.parseJSON(data);
                if (json_obj.status == 0) {
                    window.location.href = "?Index";
                }
                else {
                    $("#login-error1").show();
                }
            },
            error: function () {
                alert("login ajax error"); // show response from the php script.
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.

    });


    /*
     * AJAX CALL FOR REGISTRATION - SEND DATA TO BACKEND AND REGISTER USER
     * ON SUCCESS: LOG IN AND LINK TO LANDING PAGE
     * ON FAILURE: SHOW ERROR BASED ON RETURN CODE
     *
     * return codes:
     * 0 - success
     * 1 - wrong input(param)
     * 2 - wrong invite code
     * 3 - user (email) already exists
     * format: { "status": code }
     */


    $(".form-register").submit(function (f) {
        $(".alert").hide(); // hide alerts on form send, to correctly display new alerts
        $.ajax({
            type: "POST",
            url: "?UserRegistration",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    Title: $('#registerinputTitle').val(),
                    FirstName: $('#registerinputFirstName').val(),
                    LastName: $('#registerinputLastName').val(),
                    Organization: $('#registerinputOrganization').val(),
                    EMail: $('#registerinputEMail').val(),
                    Password: $('#registerinputPassword').val(),
                    InviteCode: $('#registerinputInviteCode').val()
                })
            },
            success: function (data) {
                console.log(data);
                var json_obj = $.parseJSON(data);
                if (json_obj.status == 0) {
                    window.location.href = "?Index";
                }
                else if (json_obj.status == 1) {
                    $("#reg-error1").show();
                }
                else if (json_obj.status == 2) {
                    $("#reg-error2").show();
                }
                else if (json_obj.status == 3) {
                    $("#reg-error3").show();
                }

            },
            error: function () {
                alert("registration ajax error"); // SHOW ALERT IF AJAX CALL FAILS
            }
        });

        f.preventDefault(); // avoid to execute the actual submit of the form.

    });
})
;

