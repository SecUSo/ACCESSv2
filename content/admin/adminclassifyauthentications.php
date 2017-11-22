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
            <h2>Change Classification Values for
                <span id="className"><? echo $data_className; ?></span> in
                <span id="featureName"><? echo $data_featureName; ?></span>
            </h2>
            <hr>
            <div class="panel panel-default">
                <div class="panel-heading">ACCESS</div>
                <div style="height: 600px; overflow: scroll" id="system-container" class="table-responsive">
                    <table id="classification-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <? if(isset($data_tableHeader)){
                                for($i = 0; $i<=count($data_tableHeader); $i++){?>
                                    <th id="head_<? echo ($i>0) ? ($i-1) : ''; ?>" name="<? echo ($i>0) ? $data_tableHeader[$i-1] : ''; ?>">
                                        <? echo ($i>0) ? str_replace('+', '<br>', $data_tableHeader[$i-1]) : ''; ?>
                                    </th>
                                <? }} ?>
                        </tr>
                    </thead>
                        <? if(isset($data_tableHeader)){
                            for($y = 0; $y<count($data_tableHeader); $y++){?>
                                <tr>
                                    <? for($x = 0; $x<=count($data_tableHeader); $x++){?>
                                        <td id="<? echo ($x==0)?'side_'.$y.'" name="'.$data_tableHeader[$y] : $y.'_'.($x-1) ;?>">
                                            <? if($x==0){ ?>
                                                <? echo str_replace('+', '<br>', $data_tableHeader[$y]); ?>
                                            <?}else{?>
                                                <? if(($x-1)==$y){ ?>
                                                    1
                                                <?}elseif(($x-1)>$y){?>
                                                    <select class="dropdown" style="position:static" id="<? echo $y; ?>_<? echo $x-1; ?>">
                                                        <? $t=$data_content[array_keys($data_content)[$y]]; ?>
                                                        <? $val=$t[array_keys($t)[$x-2-$y]]; ?>
                                                        <option value="3/2"<? echo ($val=="1.5")?' selected="selected"':'';?>>3/2</option>
                                                        <option value="1"<? echo ($val=="1")?' selected="selected"':'';?>>1</option>
                                                        <option value="2/3"<? echo ($val=="0.666667")?' selected="selected"':'';?>>2/3</option>
                                                    </select>
                                                <?} ?>
                                            <?} ?>
                                        </td>
                                    <?}?>
                                </tr>
                            <? }} ?>
                    </table>
                    <div class="ui-button"><button id="send_scales">Save Scale-Values</button></div>
                </div>
            </div>
        </div><!-- /.content -->
    </div> <!-- row -->
</div>
<script src="js/tableHeadFixer.js"></script>
<script src="js/adminclassifyauthentications.js"></script>
