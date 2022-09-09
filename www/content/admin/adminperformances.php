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
<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h2>Set/View Performances in Feature <span id="feature"><? echo $data_param; ?></span></h2>
            <hr>
            <div id="classifyauth" class="panel panel-default">
                <div class ="panel-heading">ACCESS</div>
                <div class="panel-body">&nbsp;
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <td>&nbsp;</td>
                                <? foreach($data_tableHeaders as $headName){ ?>
                                    <td><? echo $headName; ?></td>
                                <? } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <? $i=0; ?>
                            <? foreach($data_content as $row => $arr) {?>
                                <tr>
                                    <td><? echo $data_tableHeaders[$i]; ?></td>
                                <? foreach($arr as $col => $val){ ?>
                                    <td><? echo number_format($val, 3, '.', ''); ?></td>
                                <? } ?>
                                </tr>
                                <? $i++; ?>
                            <? } ?>
                            <tr id="placeholder-row" style="height:50px"></tr>
                            <tr id="performances-row">
                                <td>Performances:</td>
                                <? foreach($data_performances as $authID => $performance){ ?>
                                    <td id="<? echo $authID; ?>"><? echo number_format($performance, 3, '.', ''); ?></td>
                                <? } ?>
                            </tr>
                            </tbody>
                        </table>
                        <br />
                        <div id="consistency">Consistency-Value (Should be lower than 10%): <? echo $data_consistency; ?></div>
                        <br />
                        <br />
                        <div id="save-performances">
                            <button>
                                Save Performances
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<script src="js/adminperformances.js"></script>