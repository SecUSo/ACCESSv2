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
<script src="js/useroverview.js"></script>

<?/* ADMIN USER OVERVIEW PAGE*/?>
<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
                <h2>All Users</h2>
                <hr>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lastname</th>
                        <th>Firstname</th>
                        <th>Email</th>
                        <th>isAdmin</th>
                        <th>Meta</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? for($it = 0; $it < count($data_content); $it++) { ?>
                        <tr>
                            <td><? echo $data_content[$it]['Id']?></td>
                            <td><? echo $data_content[$it]['LastName']?></td>
                            <td><? echo $data_content[$it]['FirstName']?></td>
                            <td><? echo $data_content[$it]['EMail']?></td>
                            <td><? echo $data_content[$it]['IsAdmin']?></td>
                            <td><a href="?AdminUser&id=<?echo $data_content[$it]['Id']?>"<button type="button" class="btn btn-primary btn-xs">Edit</button></a>
                                <? if ($data_content[$it]['AccountStatus'] == "activated") { ?>
                                    <button type="button" userID="<?echo $data_content[$it]['Id']?>"  class="btn btn-warning btn-xs btn-toggle">Disable</button>
                               <? } else { ?>
                                <button type="button" userID="<?echo $data_content[$it]['Id']?>"  class="btn btn-success btn-xs btn-toggle">Enable</button>
                                <? } ?>
                                <button type="button" userID="<?echo $data_content[$it]['Id']?>" class="btn btn-danger btn-xs btn-delete">Delete</button>
                            </td>
                        </tr>
                    <? }; ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>