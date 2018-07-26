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
<? /* LINK TO JAVASCRIPT FILE*/ ?>
<script src="js/index.js"></script>
<? /* LANDINGPAGE FOR ACCESS SITE - JUMBOTRON VIEW WITH LINKS TO ALL CONTENT PAGES*/ ?>
<div class="container">
    <a id="scroll_up_button" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to go up"
       data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>

    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron">
                <h1>ACCESS PLATFORM</h1>

                <h2>Information and Evaluation of Authentication Schemes / Decision Support</h2>
            </div>
            <hr>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Authentication Schemes</a></li>
                <li><a data-toggle="tab" href="#menu1">Features</a></li>
                <? if ($data_validSession) { ?>
                    <li><a data-toggle="tab" href="#menu2">Authentication Scheme Suggestion</a></li><? } ?>
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
                        <? /* GET ALL AUTHENTICATIONS BY ID AND PRINT THEM IN TABLE*/ ?>
                        <? for ($it = 0; $it < count($data_content); $it++) { ?>
                            <tr class='clickable-row' data-href='?Content&id=<? echo $data_content[$it]['id'] ?>'>
                                <td><? echo $data_content[$it]['id'] ?></td>
                                <td><? echo $data_content[$it]['name'] ?></td>
                                <td><? echo $data_content[$it]['category'] ?></td>
                            </tr>
                        <? }; ?>
                        </tbody>
                    </table>
                </div>

                <div id="menu1" class="tab-pane fade" style="margin-top: 20px;">
                    <? for ($it = 0; $it < count($data_featureOverview); $it++) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading"><? echo $data_featureOverview[$it]['name'] ?></div>
                            <div class="panel-body">
                                <ul>
                                    <? for ($itx = 0; $itx < count($data_featureOverview[$it]['features']); $itx++) { ?>
                                        <li>
                                        <a href="?Feature&id=<? echo $data_featureOverview[$it]['features'][$itx]['id'] ?>">
                                            <? echo $data_featureOverview[$it]['features'][$itx]['name'] ?>
                                        </a>
                                        <ul>
                                            <? for ($ity = 0; $ity < count($data_featureOverview[$it]['features'][$itx]['subfeatures']); $ity++) { ?>
                                                <li>
                                                    <a href="?Subfeature&id=<? echo $data_featureOverview[$it]['features'][$itx]['subfeatures'][$ity]['id'] ?>">
                                                        <? echo $data_featureOverview[$it]['features'][$itx]['subfeatures'][$ity]['name'] ?>
                                                    </a>
                                                </li>
                                            <? }; ?>
                                        </ul></li><? }; ?>
                                </ul>
                            </div>
                        </div>  <? }; ?>
                </div>
                <div id="menu2" class="tab-pane fade" style="margin-top: 20px;">
                    <div id="addNewAuthPanel1">
                        <div class="jumbotron">
                            <h2>Step 1: Basic Data</h2>

                            <div class="progress">
                                <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">0% Complete</span>
                                </div>
                            </div>
                            <p>Fill in basic data of the new authentication scheme.</p>
                            <nav aria-label="...">
                                <ul class="pager">
                                    <li id="proceed1" class="next showResult"><a href="javascript:void(0)"><b>Subfeatures
                                                <span
                                                        aria-hidden="true">→</span></b></a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <ul id="system-container">
                                    <form>
                                        <p class="text-right">* Required fields</p>

                                        <div class="form-group">
                                            <label for="formAddNewAuth_Name">Name (*)</label>
                                            <input type="text" class="form-control" id="formAddNewAuth_Name"
                                                   placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="formAddNewAuth_Description">Description (*)</label>
                                            <textarea class="form-control" rows="5"
                                                      id="formAddNewAuth_Description"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="formAddNewAuth_Category">Category (*)</label>
                                            <select class="form-control" id="formAddNewAuth_Category_Preselection">
                                                <option>New category</option>
                                                <? foreach ($data_suggestion_new_auth_category_list as $test) { ?>
                                                    <option><? echo $test["category"]; ?></option>
                                                <? } ?>
                                            </select>
                                            </br>
                                            <input type="text" class="form-control" id="formAddNewAuth_Category"
                                                   placeholder="Enter new category (select 'New category' in previous menu)">
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                        <div
                                style="padding-left: 60px;padding-right: 60px;border-radius: 6px;background-color: #eee;height: 52px;">
                            <nav aria-label="...">
                                <ul class="pager">
                                    <li id="proceed1_bottom" class="next showResult">
                                        <a href="javascript:void(0)" style="margin-top: 10px;">
                                            <b>Subfeatures <span aria-hidden="true">→</span></b>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="hidden" id="addNewAuthPanel2">
                        <div class="jumbotron">
                            <h2>Step 2: Select Subfeatures</h2>

                            <div class="progress">
                                <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="33"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                                    <span class="sr-only">33% Complete</span>
                                </div>
                            </div>
                            <p>Select subfeatures which are fulfilled by the new authentication scheme.</p>

                            <p>For selective features with an included nullclass, exactly one subfeature must be
                                selected.</p>

                            <p>Hover your mouse over features and sub-features to get detailed information about it.</p>
                            <nav aria-label="...">
                                <ul class="pager">
                                    <li id="proceed0" class="previous showFeatures"><a
                                                href="javascript:void(0)"><b><span
                                                        aria-hidden="true">←</span> Basic Data</b></a></li>
                                    <li id="proceed2" class="next showResult"><a href="javascript:void(0)"><b>Classification
                                                <span
                                                        aria-hidden="true">→</span></b></a></li>
                                </ul>
                            </nav>
                        </div>
                        <div id="authsubfeature" class="panel panel-default">
                            <div class="panel-body" id="suggestion_ec_body">
                                <div class="text-center">
                                    <span style="font-size: small;" class="label label-default">Additive Feature: |subfeatures| >= 0</span>
                                    <span style="font-size: small;" class="label label-primary">Selective Feature excluding nullclass: |subfeatures| <= 1</span>
                                    <span style="font-size: small;" class="label label-info">Selective Feature including nullclass: |subfeatures| == 1</span>
                                </div>
                                </br>
                                <div>
                                    <? if (isset($data_suggestion_clean_auth_template))
                                        foreach ($data_suggestion_clean_auth_template as $categoryName => $cArr) {
                                            ?>
                                            <div class="category" id="<? echo $categoryName; ?>">
                                                <span><? echo $categoryName; ?></span>
                                                <? if (isset($cArr))
                                                    foreach ($cArr as $featureName => $fArr) {
                                                        ?>
                                                        <div class="feature" id="<? echo $featureName; ?>">
                                                            <? if (in_array($featureName, $selectiveFeaturesWithNullclass))
                                                                echo '<span class="label label-primary scheme_info_box" data-content="' . $data_suggestion_clean_auth_template_feature_descriptions[$featureName] . '" title="Description" rel="popover"
                                                                                    data-placement="top"
                                                                                    data-trigger="hover">' . $featureName . '</span>';
                                                            else if (in_array($featureName, $selectiveFeatures))
                                                                echo '<span class="label label-info scheme_info_box" data-content="' . $data_suggestion_clean_auth_template_feature_descriptions[$featureName] . '" title="Description" rel="popover"
                                                                                    data-placement="top"
                                                                                    data-trigger="hover">' . $featureName . '</span>';
                                                            else if (in_array($featureName, $additiveFeatures))
                                                                echo '<span class="label label-default scheme_info_box" data-content="' . $data_suggestion_clean_auth_template_feature_descriptions[$featureName] . '" title="Description" rel="popover"
                                                                                    data-placement="top"
                                                                                    data-trigger="hover">' . $featureName . '</span>';
                                                            ?>
                                                            <ul style="list-style-type:disc; padding-left: 0px;">
                                                                <? if (isset($fArr))
                                                                    foreach ($fArr as $subFeatureName => $inAuth) {
                                                                        ?>
                                                                        <li style="list-style-type: none; margin-top: 5px;">
                                                                            <div class="subfeature"
                                                                                 id="<? echo $subFeatureName; ?>">

                                                                                <? if ($inAuth) { ?>
                                                                                    <img src="img/cross.png"
                                                                                         class="in-auth">
                                                                                <? } else { ?>
                                                                                    <img src="img/dash.png"
                                                                                         class="not-in-auth">
                                                                                <? } ?>
                                                                                <span class="scheme_info_box"
                                                                                      data-content="<? echo $data_suggestion_clean_auth_template_descriptions[$subFeatureName]; ?>"
                                                                                      title="Description" rel="popover"
                                                                                      data-placement="top"
                                                                                      data-trigger="hover">
                                                                                <? echo $subFeatureName; ?></span>
                                                                            </div>
                                                                        </li>
                                                                    <? } ?>
                                                            </ul>
                                                        </div>
                                                    <? } ?>
                                            </div>
                                        <? } ?>
                                </div>
                            </div>
                        </div>
                        <div
                                style="padding-left: 60px;padding-right: 60px;border-radius: 6px;background-color: #eee;height: 52px;">
                            <nav aria-label="...">
                                <ul class="pager">
                                    <li id="proceed0_bottom" class="previous showFeatures"><a
                                                href="javascript:void(0)" style="margin-top: 10px;"><b><span
                                                        aria-hidden="true">←</span> Basic Data</b></a></li>
                                    <li id="proceed2_bottom" class="next showResult"><a href="javascript:void(0)"
                                                                                        style="margin-top: 10px;"><b>Classification
                                                <span
                                                        aria-hidden="true">→</span></b></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="hidden" id="addNewAuthPanel3">
                        <div class="jumbotron">
                            <h2>Step 3: Classification</h2>

                            <div class="progress">
                                <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="66"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 66%">
                                    <span class="sr-only">66% Complete</span>
                                </div>
                            </div>
                            <p>Rate new authentication scheme within all subclasses</p>
                            <nav aria-label="...">
                                <ul class="pager">
                                    <li id="proceed1_copy" class="previous showFeatures"><a
                                                href="javascript:void(0)"><b><span
                                                        aria-hidden="true">←</span> Subfeatures</b></a></li>
                                    <li id="proceed3" class="next showResult"><a
                                                href="javascript:void(0)"><b>Send Suggestion <span
                                                        aria-hidden="true">→</span></b></a></li>
                                </ul>
                            </nav>
                        </div>
                        <div id="authsubfeature" class="panel panel-default">
                            <div class="panel-body" id="suggestion_ec_body">
                                <div style="overflow: auto" id="classification_placeholder"></div>
                            </div>
                        </div>
                        <div
                                style="padding-left: 60px;padding-right: 60px;border-radius: 6px;background-color: #eee;height: 52px;">
                            <nav aria-label="...">
                                <ul class="pager">
                                    <li id="proceed1_copy_bottom" class="previous showFeatures"><a
                                                href="javascript:void(0)" style="margin-top: 10px;"><b><span
                                                        aria-hidden="true">←</span> Subfeatures</b></a></li>
                                    <li id="proceed3_bottom" class="next showResult"><a
                                                href="javascript:void(0)" style="margin-top: 10px;"><b>Send Suggestion <span
                                                        aria-hidden="true">→</span></b></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="hidden" id="addNewAuthPanel4">
                        <div class="jumbotron">
                            <h2>Step 4: Suggestion Sent</h2>

                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped"
                                     role="progressbar"
                                     aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <span class="sr-only">100% Complete</span>
                                </div>
                            </div>
                            <p>Your authentication scheme suggestion has been successfully sent!</p>
                            <a href="?Index" class="">Back to startpage</a>
                        </div>
                    </div>


                </div>

            </div>

            <hr>
        </div>
    </div>
</div>
</div>
