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
                        <td><? echo $data_subfeatures[$it]['name'] ?></td>
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
                                    <h4 class="timeline-title"><? echo $timeline_entry["log_title"]; ?></h4>
                                    <? if ($data_isAdmin) { ?>
                                        <button type="button"
                                                id="<? echo $timeline_entry["id"]; ?>"
                                                class="close timeline-close-btn">×
                                        </button>
                                    <? } ?>
                                </div>
                                <div class="timeline-body">
                                    <p><? echo $timeline_entry["log_text"]; ?></p>
                                </div>
                            </div>
                        </li>
                    <? } ?>
                </ul>
            </div>


            <hr>
            <? include 'comment.php'; ?>
        </div> <!-- row -->
    </div> <!-- /container -->