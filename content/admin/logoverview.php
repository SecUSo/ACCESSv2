<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2017   Christian Engelbert, Philip Stumpf
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
            <div class="content-block col-sm-4">
                <h2 class="auth_name">Top Features</h2>
                <hr>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Quantity</th>
                        <th>Request</th>
                    </tr>
                    </thead>
                    <tbody>
                <? $i = 0; foreach($dataFeatures as $logObject){ ?>
                    <tr>
                        <td><? echo $logObject->quantity; ?></td>
                        <td class="clickable-json" id="<? echo $i; ?>">
                            <? echo htmlspecialchars($logObject->jsondata); ?>
                        </td>
                        </tr>
                <? $i++;} ?>
                    </tbody>
                </table>
            </div>
            <div class="content-block col-sm-4">
                <h2 class="auth_name">Top SubFeautres</h2>
                <hr>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Quantity</th>
                        <th>Request</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? $i = 0; foreach($dataSubfeaturesAnd as $logObject2){ ?>
                        <tr>
                            <td><? echo $logObject2->quantity; ?></td>
                            <td class="clickable-json" id="<? echo $i; ?>">
                                <? echo htmlspecialchars($logObject2->jsondata); ?>
                            </td>
                        </tr>
                        <? $i++;} ?>
                    </tbody>
                </table>
            </div>
            <div class="content-block col-sm-4">
                <h2 class="auth_name">Top Features</h2>
                <hr>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Quantity</th>
                        <th>Request</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? $i = 0; foreach($dataSubfeaturesOr as $logObject3){ ?>
                        <tr>
                            <td><? echo $logObject3->quantity; ?></td>
                            <td class="clickable-json" id="<? echo $i; ?>">
                                <? echo htmlspecialchars($logObject3->jsondata); ?>
                            </td>
                        </tr>
                        <? $i++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
