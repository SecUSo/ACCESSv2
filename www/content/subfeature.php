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

<?/* BASIC CONTENT PAGE FOR "WIKI LIKE VIEW", DISPLAYS INFO ABOUT SINGLE AUTHENTICATIONS*/?>

<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h1 class="auth_name"><?php echo $data_subfeatures[0]['name'] ?></h1>
            <hr>
            <h2>Description</h2>
            <span class="auth_desc"><?php echo $data_subfeatures[0]['description'] ?></span>
            <hr>
            <? include 'comment.php'; ?>
    </div> <!-- row -->
</div> <!-- /container -->