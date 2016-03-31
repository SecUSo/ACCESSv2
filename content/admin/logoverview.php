<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Engelbert
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
?>
<div id="logoverview">
<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h2 class="auth_name">DECISION LOG</h2>
            <hr>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Request</th>
                </tr>
                </thead>
                <tbody>
            <? $i = 0; foreach($data as $logObject){ ?>
                <tr>
                    <td><? echo $logObject->quantity; ?></td>
                    <td class="clickable-json" id="<? echo $i; ?>" value="<?
                    echo htmlspecialchars($logObject->jsondata);
                    ?>">
                        <p class="glyphicon-hoverpointer">Expand&nbsp;<span class="glyphicon glyphicon-th-large"></span></p></td>
                    </tr>
            <? $i++;} ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="container">
        <p>
            <a href="#" id="close">close</a>
        </p>
        <p id="changeText">

        </p>
    </div>
    <div id="overlay"></div>
</div>
</div>

<script src="js/logoverview.js"></script>