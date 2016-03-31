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
            <h2>Classify Authentications in Feature
                <span class="featureName" id="<? echo $data_featureName; ?>"><? echo $data_featureName; ?></span>
            </h2>
            <hr>
            <div id="classifyauth" class="panel panel-default">
                <div class="panel-heading">ACCESS</div>
                <div class="panel-body">
                    <div>
                        <? foreach($data_content as $className){?>
                        <div class="feat-class" id="<? echo $className; ?>">
                            <span><? echo $className; ?></span>
                        </div>
                        <?}?>
                    </div>
            </div>
        </div><!-- /.content -->
    </div> <!-- row -->
</div>

<script src="js/adminclassifyauthenticationsclassesoverview.js"></script>