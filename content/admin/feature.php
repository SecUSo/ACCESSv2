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
<script src="js/admineditfeatures.js"></script>


<?/* CONTENT EDIT*/?>
<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h2 class="auth_name">CONTENT EDIT</h2>
            <hr>

            <form role="form" class="form-editcontent">
                <div class="form-group">
                    <label for="id">ID</label>
                    <input type="text" class="form-control" id="id" value="<? echo $data_features[0]['id'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" value="<? echo $data_features[0]['name'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="5" id="description"><? echo $data_features[0]['description'] ?></textarea>
                </div>
                <p><button type="submit" class="btn btn-default">Submit</button></p>

            </form>
        </div>
    </div>
</div>