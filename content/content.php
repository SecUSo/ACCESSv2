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
<? /* BASIC CONTENT PAGE FOR "WIKI LIKE VIEW", DISPLAYS INFO ABOUT SINGLE AUTHENTICATIONS*/ ?>

<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h1 class="auth_name"><?php echo $content_name ?></h1>
            <span class="auth_category">Category: <?php echo $content_category ?></span>
            <hr>
            <ul class="nav nav-tabs">
                <li class="active" id="firstTab"><a data-toggle="tab" href="#description">Description and Timeline</a></li>
                <li class="" id="secondTab"><a data-toggle="tab" href="#discussion">Discussion and Suggestion</a></li>
                <li class="" id="thirdTab"><a data-toggle="tab" href="#references">References</a></li>
            </ul>

            <div class="tab-content" >
                <div id="description" class="tab-pane fade in active" style="margin-top: 20px;">
                    <hr>
                    <h2>Description</h2>
                    <span class="auth_desc"><?php echo $content_description ?></span>
                    <hr>
                    <h2>Subfeatures</h2>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? for ($it = 0; $it < count($data_subfeatures); $it++) { ?>
                            <tr>
                                <td><? echo $data_subfeatures[$it]['id'] ?></td>
                                <td>
                                     <span class="subfeature_info_box pull-left"
                                           data-content="<?php echo $data_subfeature_descriptions[$data_subfeatures[$it]['name']];?>"
                                           title="Description" rel="popover"  data-placement="top" data-trigger="hover"><? echo $data_subfeatures[$it]['name'] ?>
                                    </span>
                                </td>
                            </tr>
                        <? }; ?>
                        </tbody>
                    </table>
                    <hr>
                    <h2>Timeline</h2>
                    <p>The timeline shows all changes made to the knowledge database regarding the current authentication scheme through submitted suggestions.</p>
                    <div class="row">
                        <ul class="timeline">
                            <?php foreach ($data_timeline as $timeline_entry) { ?>
                                <li>
                                    <div class="timeline-badge"><i class="glyphicon glyphicon-check"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <a onclick="toggleFunction()"  href="?Content&id=<? echo $timeline_entry["auth_authentication"]."#discussion_id_".$timeline_entry["discussion_id"]; ?>"><h4><? echo $timeline_entry["log_title"]; ?></h4></a>
                                            <!-- removed button for timeline entry deletion -->
                                            <script>
                                                function toggleFunction(){
                                                    $("#firstTab").removeClass("active");
                                                    $("#secondTab").addClass("active");
                                                    $("#description").removeClass("in active");
                                                    $("#discussion").addClass("in active");

                                                }
                                            </script>
                                        </div>
                                        <div class="timeline-body">
                                            <p><? echo $timeline_entry["log_text"]; ?></p>
                                        </div>
                                        <hr>
                                    </div>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
                <div id="discussion" class="tab-pane fade" style="margin-top: 20px;"><? include 'comment.php'; ?></div>
                <div id="references" class="tab-pane fade" style="margin-top: 20px;"><? include 'bibtexHandler.php'; ?></div>
            </div>
            <script>
                    $('.subfeature_info_box').popover({
                        container:'body'
                    });
            </script>
        </div> <!-- row -->
    </div> <!-- /container -->