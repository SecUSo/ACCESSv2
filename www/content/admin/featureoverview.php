<?
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
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <? for($it = 0; $it < count($data_featureOverview); $it++) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><? echo $data_featureOverview[$it]['name']?></div>
                    <div class="panel-body">
                        <ul>
                <? for($itx = 0; $itx < count($data_featureOverview[$it]['features']); $itx++) { ?>
                        <li>
                            <a href="?AdminFeature&id=<? echo $data_featureOverview[$it]['features'][$itx]['id']?>">
                                <? echo $data_featureOverview[$it]['features'][$itx]['name']?>
                            </a>
                            <ul>
                         <? for($ity = 0; $ity < count($data_featureOverview[$it]['features'][$itx]['subfeatures']); $ity++) { ?>
                            <li>
                                <a href="?AdminSubfeature&id=<? echo $data_featureOverview[$it]['features'][$itx]['subfeatures'][$ity]['id']?>">
                                <? echo $data_featureOverview[$it]['features'][$itx]['subfeatures'][$ity]['name']?>
                                </a>
                            </li>
                        <? }; ?>
                        </ul></li><? }; ?>
                        </ul></div></div>  <? }; ?>
            <hr>
        </div>
    </div>
</div>