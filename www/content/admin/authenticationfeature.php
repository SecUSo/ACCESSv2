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
            <h2>Change SubFeatures of <span id="authName"><? echo strip_tags($data_authName); ?></span></h2>
            <hr>
            <div id="authsubfeature" class="panel panel-default">
                <div class ="panel-heading">ACCESS</div>
                <div id="system-container">
                    <div>
                        <? if(isset($data_content))
                            foreach($data_content as $categoryName => $cArr){?>
                                <div class="category" id="<? echo $categoryName; ?>">
                                    <span><? echo $categoryName; ?></span>
                                <? if(isset($cArr))
                                    foreach($cArr as $featureName => $fArr){?>
                                    <div class="feature" id="<? echo $featureName; ?>">
                                        <span><? echo $featureName; ?></span>
                                        <? if(isset($fArr))
                                            foreach($fArr as $subFeatureName => $inAuth){?>
                                                <div class="subfeature" id="<? echo $subFeatureName; ?>">
                                                    <span><? echo $subFeatureName; ?></span>
                                                    <? if($inAuth){ ?>
                                                        <img src="img/cross.png" class="in-auth">
                                                    <? }else{ ?>
                                                        <img src="img/dash.png" class="not-in-auth">
                                                    <? } ?>
                                                </div>
                                        <? } ?>
                                    </div>
                                <? } ?>
                                </div>
                        <? } ?>
                    </div>
                    <button>Save SubFeatures for Authentication Scheme</button>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<!-- PARSER-->
<script src="js/adminauthenticationfeature.js"></script>