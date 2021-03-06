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
 * AJAX Requests for Admin Edit Content Page
 * -> Takes Input and sends to respective Controller to change Content of Pages
 **/

$(document).ready(function () {

    $(".form-editcontent").submit(function (e) {
        $.ajax({
            type: "POST",
            url: "?AdminEditContent",
            //dataType: 'json',
            data: {
                "json_data": JSON.stringify({
                    id: $('#id').val(),
                    description: $('#description').val()
                })
            },
            success: function (data) {
               window.location.href = "?AdminContentOverview";
            },
            error: function () {
                alert("Ajax error"); // show response from the php script.
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.

    });

})
;

