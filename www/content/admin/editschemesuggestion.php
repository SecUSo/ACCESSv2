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
<script src="js/admineditschemesuggestion.js"></script>

<style type="text/css">
    tr.collapse.in {
        display: table-row;
    }

    .chevron-fix {
        margin-top: -16px;
    }

</style>

<? /* CONTENT EDIT*/ ?>
<div class="container">
    <div class="row">
        <h1>Edit Scheme Suggestion</h1>
        <div class="content-block col-sm-12">
            <h2>Meta</h2>
            <hr>
            <form role="form" class="form-editcontent">
                <div class="form-group">
                    <label for="id">Id</label>
                    <input type="text" class="form-control" id="id" value="<? echo $data_scheme["id"] ?>" disabled="">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" value="<? echo $data_scheme["name"] ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="5"
                              id="description"><? echo $data_scheme["description"] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" class="form-control" id="category" value="<? echo $data_scheme["category"] ?>">
                </div>
                <div class="form-group">
                    <label for="description">User</label>
                    <input type="text" class="form-control" id="category"
                           value="<? echo $data_scheme["FirstName"] . " " . $data_scheme["LastName"] ?>" disabled="">
                </div>
                <div class="form-group">
                    <label for="description">Date</label>
                    <input type="text" class="form-control" id="date" value="<? echo $data_scheme["suggestion_date"] ?>"
                           disabled="">
                </div>

            </form>

            <div class="pull-right">
                <button type="submit" class="btn btn-primary submit_classvalues">Save</button>
                <a href="?AdminSuggestionOverview">
                    <button type="submit" class="btn btn-default">Return without saving</button>
                </a>
            </div>
        </div>
        </br>
        <div class="content-block col-sm-12">
            <h2>Subfeatures</h2>
            <hr>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($data_subfeatures as $subfeature) {
                    ?>
                    <tr>
                        <td><? echo $subfeature["id"] ?></td>
                        <td><? echo $subfeature["name"] ?></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary submit_classvalues">Save</button>
                <a href="?AdminSuggestionOverview">
                    <button type="submit" class="btn btn-default">Return without saving</button>
                </a>
            </div>
        </div>
        </br>
        <div class="content-block col-sm-12">
            <h2>New classes</h2>
            <hr>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>CLASSNAME</th>
                    <th>FEATURE</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($data_new_classes as $class) {
                    ?>
                    <tr>
                        <td><? echo $class["new_class_name"] ?></td>
                        <td><? echo $class["feature_name"] ?></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary submit_classvalues">Save</button>
                <a href="?AdminSuggestionOverview">
                    <button type="submit" class="btn btn-default">Return without saving</button>
                </a>
            </div>
        </div>
        </br>
        <div class="content-block col-sm-12">
            <h2>Classvalues</h2>
            <hr>

            <?php

            $latestFeature = "";
            $panel_id = 0;
            $panel_list = array();
            foreach ($data_classvalues as $classvalue) {
                if ($classvalue["feature"] != $latestFeature) {
                    $panel_list[] = array("feature" => $classvalue["feature"],
                        "class" => $classvalue["class"],
                        "id" => $panel_id++);
                    $latestFeature = $classvalue["feature"];
                }
            }

            foreach ($panel_list as $panel) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading classification-panel" data-toggle="collapse"
                         data-target="#collapse_panel_<? echo $panel["id"]; ?>">
                        <h4 class="panel-title">
                            <b>Feature:</b> <? echo $panel["feature"] ?> <b>Class:</b> <? echo $panel["class"] ?>
                        </h4>
                        <span class="pull-right chevron-fix"><i class="glyphicon glyphicon-chevron-down"></i></span>
                    </div>
                    <div id="collapse_panel_<? echo $panel["id"]; ?>" class="panel-collapse collapse">
                        <table id="classvalues" class="table table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>1. AUTH SCHEME</th>
                                <th>2. AUTH SCHEME</th>
                                <th>VALUE</th>
                            </tr>
                            </thead>
                            <tbody>

                            <div id="collapse_panel_<? echo $panel["id"]; ?>" class="panel-collapse collapse">
                                <?php
                                foreach ($data_classvalues as $classvalue) {
                                    if ($classvalue["feature"] == $panel["feature"]) {
                                        ?>

                                        <tr>
                                            <td cat_feature="<? echo $classvalue["cat_feature"] ?>"></td>
                                            <td cat_class_feature="<? echo $classvalue["cat_class_feature"] ?>"></td>
                                            <td auth_authentication_1="<? echo $classvalue["auth_authentication_1"]; ?>"><?
                                                if ($classvalue["auth_authentication_1"] == -1)
                                                    echo $data_scheme["name"];
                                                else
                                                    echo $data_authIds[$classvalue["auth_authentication_1"]];
                                                ?></td>

                                            <td auth_authentication_2="<? echo $classvalue["auth_authentication_2"]; ?>"><?
                                                if ($classvalue["auth_authentication_2"] == -1)
                                                    echo $data_scheme["name"];
                                                else
                                                    echo $data_authIds[$classvalue["auth_authentication_2"]];
                                                ?></td>
                                            <td>
                                                <select class="dropdown" id="0_1">
                                                    <option value="3/2" <? if ($classvalue["value"] == 1.5) echo "selected" ?>>
                                                        better
                                                    </option>
                                                    <option value="1" <? if ($classvalue["value"] == 1.0) echo "selected" ?>>
                                                        equal
                                                    </option>
                                                    <option value="2/3" <? if ($classvalue["value"] == 0.666667 || $classvalue["value"] == 0.7) echo "selected" ?>>
                                                        worse
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?
                                    }
                                }
                                ?>
                            </div>
                            </tbody>
                        </table>
                    </div>
                </div>

            <? } ?>

            <div class="pull-right">
                <button type="submit" class="btn btn-primary submit_classvalues">Save</button>
                <a href="?AdminSuggestionOverview">
                    <button type="submit" class="btn btn-default">Return without saving</button>
                </a>
            </div>
        </div>

    </div>
    </br>
    </br>
</div>