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
<?/* LINK TO JAVASCRIPT FILE*/?>
<script src="js/index.js"></script>
<?/* LANDINGPAGE FOR ACCESS SITE - JUMBOTRON VIEW WITH LINKS TO ALL CONTENT PAGES*/?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron">
                <h1>ACCESS PLATFORM</h1>
                <h2>Information and Evaluation of Authentications / Decision Support</h2>
            </div>
            <hr>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Authentications</a></li>
                <li><a data-toggle="tab" href="#menu1">Features</a></li>
            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane fade in active" style="margin-top: 20px;">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>CATEGORY</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?/* GET ALL AUTHENTICATIONS BY ID AND PRINT THEM IN TABLE*/?>
                            <? for($it = 0; $it < count($data_content); $it++) { ?>
                                <tr class='clickable-row' data-href='?Content&id=<? echo $data_content[$it]['id']?>'>
                                    <td><? echo $data_content[$it]['id']?></td>
                                    <td><? echo $data_content[$it]['name']?></td>
                                    <td><? echo $data_content[$it]['category']?></td>
                                </tr>
                            <? }; ?>
                        </tbody>
                    </table>
                </div>

                <div id="menu1" class="tab-pane fade" style="margin-top: 20px;">
            <? for($it = 0; $it < count($data_featureOverview); $it++) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><? echo $data_featureOverview[$it]['name']?></div>
                    <div class="panel-body">
                        <ul>
                            <? for($itx = 0; $itx < count($data_featureOverview[$it]['features']); $itx++) { ?>
                                <li>
                                <a href="?Feature&id=<? echo $data_featureOverview[$it]['features'][$itx]['id']?>">
                                    <? echo $data_featureOverview[$it]['features'][$itx]['name']?>
                                </a>
                                <ul>
                                    <? for($ity = 0; $ity < count($data_featureOverview[$it]['features'][$itx]['subfeatures']); $ity++) { ?>
                                        <li>
                                            <a href="?Subfeature&id=<? echo $data_featureOverview[$it]['features'][$itx]['subfeatures'][$ity]['id']?>">
                                                <? echo $data_featureOverview[$it]['features'][$itx]['subfeatures'][$ity]['name']?>
                                            </a>
                                        </li>
                                    <? }; ?>
                                </ul></li><? }; ?>
                        </ul></div></div>  <? }; ?>
            </div>
                </div>

                <hr>
            </div>
        </div>
    </div>
</div>
