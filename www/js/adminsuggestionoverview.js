/**
 * #####################################################################################################################
 * Copyright (C) 2017   Thomas Weber
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


$(document).ready(function () {

    $(".accept").click(function (e) {
        if (!confirm("Accept suggestion?")) {
            e.preventDefault();
        }
        else {
            $(".loader").show();
        }
    });

    $(".reject").click(function (e) {
        if (!confirm("Reject suggestion?")) {
            e.preventDefault();
        }
        else {
            $(".loader").show();
        }
    });

    $(".delete").click(function (e) {
        if (!confirm("Delete suggestion?")) {
            e.preventDefault();
        }
        else {
            $(".loader").show();
        }
    });

});

