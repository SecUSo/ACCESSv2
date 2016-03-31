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
            <h2>Add/Change Categories</h2>
            <hr>
            <div class="panel panel-default">
                <div class ="panel-heading">ACCESS</div>
                <ul id="system-container">
                    <div>
                        <? foreach($data_content as $categoryName => $cArr) {?>
                            <div class="category" id="<? echo $categoryName; ?>">
                                <img src="img/dash.png" class="remove-cat" id="<? echo $categoryName; ?>">
                                <span id="<? echo $categoryName; ?>"><? echo $categoryName; ?></span>

                                <? foreach($cArr as $featureName => $fArr) {if($featureName != NULL){?>
                                <div style="margin-left:20px" class="feature" id="<? echo $featureName; ?>">
                                    <img src="img/dash.png" class="remove-feat" id="<? echo $featureName; ?>">
                                    <span id="<? echo $featureName; ?>"><? echo $featureName; ?></span>

                                    <? foreach($fArr as $subFeatureName) {if($subFeatureName != NULL){?>
                                    <div style="margin-left:20px" class="subfeature" id="<? echo $subFeatureName; ?>">
                                        <img src="img/dash.png" class="remove-sub" id="<? echo $subFeatureName; ?>">
                                        <span id="<? echo $subFeatureName; ?>"><? echo $subFeatureName; ?></span>
                                    </div>
                                    <? }} ?>
                                    <div style="margin-left:20px" class="new_subfeature">
                                        <img src="img/cross.png" class="add-sub" id="<? echo $featureName; ?>">
                                        <input type="text" value="Add new Subfeature" id="<? echo $featureName; ?>">
                                    </div>

                                </div>
                                <? }} ?>

                                <div style="margin-left:20px" class="new_feature">
                                    <img src="img/cross.png" class="add-feat" id="<? echo $categoryName; ?>">
                                    <input type="text" value="Add new Feature" id="<? echo $categoryName; ?>">
                                </div>

                            </div>
                        <? } ?>

                        <div class="new_category">
                            <img src="img/cross.png" class="add-cat">
                            <input type="text" value="Add new Category">
                        </div>
                    </div>
                    <button>Change Structure</button>
                </ul>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<!-- PARSER-->
<script src="js/admincategoriesoverview.js"></script>