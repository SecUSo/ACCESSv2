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

<?/* LANDINGPAGE FOR ACCESS SITE - JUMBOTRON VIEW WITH LINKS TO ALL CONTENT PAGES*/?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2>Content Overview</h2>
            <hr>
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
                        <td><a href="?AdminContent&id=<? echo $data_content[$it]['id']?>"<button type="button" class="btn btn-primary btn-xs">Edit</button></a></td>
                    </tr>
                <? }; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>