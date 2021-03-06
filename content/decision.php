<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2017   Philip Stumpf
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

<?php  /** Frontend of Decision Support Plattform */  ?>
<link href="css/decision.css" rel="stylesheet">
<h2><span id="counter" class="label label-default pull-right">Result Count<br/><br><span id="counter-value">0</span>/<span id="counter-max">0</span></span></h2>

<div id="decision_platform">
    <div class="container">
        <?php  /** STEP 1: Feature Selection -> Drag and Drop of all Features */  ?>
        <div id="feature-selection" class="row">
            <div class="content-block col-sm-12">
                <div class="jumbotron">
                    <h2>Step 1: Choose Features</h2>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                            <span class="sr-only">33% Complete</span>
                        </div>
                    </div>
                    <p>Choose the features that are important for you and align them in descending order on the right hand side.
                        Features in the same group are equally important.</p>
                    <button id="show_ex1" type="button" class="btn btn-info">Show/Hide Example</button>
                    <img id="example_step1" class="example_image hidden" src="img/example_step1.jpg" alt="example" height="auto" width="100%">
                    <hr>
                    <nav aria-label="...">
                        <ul class="pager">
                            <li class="next showSubFeatures"><a href="javascript:void(0)"><b>Select Hard-Constraints <span aria-hidden="true">&rarr;</span></b></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="content-block col-sm-6">
                <ol id="baseList" class="list-group droptrue">
                    <? foreach($cat_feat_subfeat as $categoryName => $cArr) {?>
                        <? foreach($cArr as $featureName => $fArr) {if($featureName != NULL){?>
                            <li data-toggle="collapse" href="#collapse-<? echo $featureName; ?>" id="<? echo $featureName; ?>" class="feature list-group-item <? echo $categoryName;?>" value="<?php echo $data_featureDescriptions[$featureName];?>">
                                <span><? echo $featureName;?></span>
                                <!-- tooltip box for subfeature info-->
                                <div class="decision_info_box pull-right"
                                     data-content="<?php echo $data_featureDescriptions[$featureName];?>"
                                     title="Description" rel="popover"  data-placement="top" data-trigger="hover">
                                    <div class="btn btn-xs pull-right info-button ">
                                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-top: 2px;"></span>
                                    </div>
                                </div>
                                <span class="label pull-right" style="margin-top: 5px;"><? echo $categoryName; ?></span>
                            </li>
                        <? }} ?>
                    <? } ?>
                </ol>
            </div>
            <div id="dropList" class="content-block col-sm-6">
                <ol class="list-group droptrue feature-list"></ol>
                <div id="toggleButtons" class="row">
                    <button id="addlist" type="button" class="btn btn-lg btn-primary pull-right">Add Group</button>
                </div>
                <div class="row">
                    <hr>
                    <button id="addRemaining" type="button" class="btn btn-lg btn-danger pull-right"">Add Remaining Features</button>
                </div>
            </div>
        </div>
    <?php  /** STEP 2: Hard Constraint Selection in conjunctive normal form */  ?>
    <div id="subFeature-selection" class="row" style="display: none;">
        <div class="content-block col-sm-12">
            <div class="jumbotron">
                <h2>Step 2: Select Hard Constraints</h2>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" style="width: 66%">
                        <span class="sr-only">66% Complete</span>
                    </div>
                </div>
                <p>Choose optional Hard Constraints that must be implemented by your wanted Authentication Scheme. </p>
                <p>
                    <span class="glyphicon glyphicon-ok aria-hidden="true"></span> : This Hard Constraint must be fulfilled <br/>
                    <span class="glyphicon glyphicon-transfer aria-hidden="true"></span> : Only one of the chosen Hard Constraints must be fulfilled<br/>
                </p>
                <button id="show_ex2" type="button" class="btn btn-info">Show/Hide Example</button>
                <img id="example_step2" class="example_image hidden" src="img/example_step2.jpg" alt="example" height="auto" width="100%">
                <hr>
                <nav aria-label="...">
                    <ul class="pager">
                        <li class="previous showFeatures"><a href="javascript:void(0)"><b><span aria-hidden="true">&larr;</span> Choose Features</b></a></li>
                        <li class="next showResult"><a href="javascript:void(0)"><b>Start Evaluation<span aria-hidden="true">&rarr;</span></b></a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="content-block col-sm-12">
            <ol id="subFeature-list" class="panel-group list-group">
                <? foreach($cat_feat_subfeat as $categoryName => $cArr) {?>
                    <? foreach($cArr as $featureName => $fArr) {if($featureName != NULL){?>
                        <li id="<? echo $featureName; ?>" class="panel panel-default feature">
                            <div class="panel-heading clearfix active <? echo $categoryName; ?>">
                                <h4 class="featurename panel-title pull-left"><? echo $featureName; ?></h4>
                                <span class="categoryname label label-info pull-right"><? echo $categoryName; ?></span>
                            </div>
                            <div>
                                <ol class="subfeature-list2 list-group">
                                    <? foreach($fArr as $subFeatureName => $statusCode) { if($subFeatureName != NULL || "0"){?>
                                        <li id="<? echo $subFeatureName; ?>" class="subfeature list-group-item" value="<?php echo $data_subFeatureDescriptions[$subFeatureName];?>">
                                            <!-- tooltip box for subfeature info-->
                                            <div class="decision_info_box pull-left"
                                                 data-content="<?php echo $data_subFeatureDescriptions[$subFeatureName];?>"
                                                 title="Description" rel="popover"  data-placement="top" data-trigger="hover">
                                                <div class="btn btn-xs pull-left info-button ">
                                                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-top: 2px;"></span>
                                                </div>
                                            </div>
                                            <span class="li-name"><? echo $subFeatureName; ?></span>
                                            <span class="clearfix">
                                            <a class="plus-button pull-right"><span class="glyphicon glyphicon-plus aria-hidden="true"></span></a>
                                            <div class="andorgroup btn-group pull-right" role="group" aria-label="...">
                                                <button type="button" class="btn btn-xs btn-default andButton"><span class="glyphicon glyphicon-ok aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-xs btn-default orButton"><span class="glyphicon glyphicon-transfer aria-hidden="true"> OR</button>
                                                <div class="or-dropdowngroup btn-group" role="group">
                                                    <button type="button" class="btn btn-xs btn-default dropdown-toggle disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Choose Subfeature
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu or-dropdown">
                                                        <? foreach($data_subFeatureDescriptions as $subFeatureNamexx => $subFeatureDescription) {?>
                                                            <? if($subFeatureNamexx != $subFeatureName) { ?>
                                                                <li class="dropdownli"><? echo $subFeatureNamexx; ?></li>
                                                            <? }} ?>
                                                    </ul>
                                                </div>
                                        </li>
                                    <? }} ?>
                                </ol>
                            </div>
                        </li>
                    <? }} ?>
                <? } ?>
            </ol>
        </div>
    </div>
    <?php  /** STEP 3: Result and  Evaluation */  ?>
    <div id="decisionResult" class="row" style="display: none">
        <div class="jumbotron">
            <h2>Step 3: Result</h2>
            <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span class="sr-only">100% Complete</span>
                </div>
            </div>
            <p>Congratulations! Here you can evaluate your result. If you want to change something you can go back and choose another input. Use the Compare function in Table to evaluate! </p>
            <p>
                <span class="glyphicon glyphicon-th-list  aria-hidden="true"></span> : Best performing Authentication Schemes with overall performance <br/>
                <span class="glyphicon glyphicon-stats aria-hidden="true"></span> : Single Performances for all selected features<br/>
                <span class="glyphicon glyphicon-flash aria-hidden="true"></span>: Authentication Schemes that failed on Hard Constraint<br/>
            </p>
            <hr>
            <nav aria-label="...">
                <ul class="pager">
                    <li class="previous showSubFeatures"><a href="javascript:void(0)"><b><span aria-hidden="true">&larr;</span> Select Hard-Constraints</b></a></li>
                </ul>
            </nav>
        </div>
        <div class="content-block col-sm-12">
            <h4><span class="glyphicon glyphicon-th-list aria-hidden="true"></span> Added Performances</h4>
            <div id="table-show-all" style="text-align: right"><a href="javascript:void(0)">Show/Hide all Results</a></div>
            <table id="changeTable" class="table .table-bordered">
                <tr><th>Authentication Scheme</th><th>Performance</th><th>Info</th></tr>
                <tr style="border-top: 2px solid black;">
                    <td><div class="btn-group">
                            <span id="compare_name">Compare</span>
                            <button type="button" class="btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Compare</span>
                            </button>
                            <ul id="compare-dropdown" class="dropdown-menu scrollable-menu" role="menu">
                                <li hidden></li>
                            </ul>
                        </div>
                    </td>
                    <td><span id="compare_perf" class="hidden">0.0</span></td>
                    <td><a id="compare_info" class="hidden" href="/" target="blank"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td>
                </tr>
            </table>
            <h4><span class="glyphicon glyphicon-stats aria-hidden="true"></span> Single Feature Performances</h4>
            <div id="changeChart">
                &nbsp;<canvas id="myChart"></canvas>
            </div>
            <h4><span class="glyphicon glyphicon-flash aria-hidden="true"></span> Filtered Authentication Schemes by Hard Constraints</h4>
            <div id="hard-constraints">
                <table id="changeTable2" class='table .table-bordered'">
                <tr><th>Authentication Scheme</th><th>Failed on Hard Constraint(s)</th><th>Information</th></tr>
                </table>
            </div>
        </div>

    </div>
</div>
</div>
<?php  /** Information Overlay Used in Step 1 */  ?><!--
<div id="info-overlay" class="overlay"> ---- overlay replaced by popover tooltip box
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div id="info-overlay-content">
        <h1 id="featoverlay-name">Authentication Scheme</h1>
        <p id="featoverlay-desc">Lorem Ipsum</p>
    </div>
</div>
<!-- Javascript Files-->
<script src="js/decisionmaking.js"></script>
<script src="js/decision_ui.js"></script>
<script src="js/decision_live.js"></script>