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
<script src="js/adminedituser.js"></script>

<?/* BASIC USER OVERVIEW PAGE*/?>
<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
                <h2>Admin Edit User Data</h2>
                <hr>
            <form role="form" class="form-edituser">
            <div class="form-group">
                <label for="Id">ID</label>
                <input type="text" class="form-control" id="Id" value="<? echo $data_userData[0]['Id'] ?>"  disabled>
            </div>

            <div class="form-group">
                <label for="Title">Title</label>
                <select class="form-control" id="Title">
                    <option selected><? echo $data_userData[0]['Title'] ?></option>
                    <option></option>
                    <option>Dr.</option>
                    <option>Prof.</option>
                    <option>Prof. Dr.</option>
                </select>
            </div>

            <div class="form-group">
                <label for="FirstName">FirstName</label>
                <input type="text" class="form-control" id="FirstName" value="<? echo $data_userData[0]['FirstName'] ?>">
            </div>

            <div class="form-group">
                <label for="LastName">LastName</label>
                <input type="text" class="form-control" id="LastName" value="<? echo $data_userData[0]['LastName'] ?>">
            </div>

            <div class="form-group">
                <label for="EMail">EMail</label>
                <input type="text" class="form-control" id="EMail" value="<? echo $data_userData[0]['EMail'] ?>">
            </div>

            <div class="form-group">
                <label for="Organization">Organization</label>
                <input type="text" class="form-control" id="Organization" value="<? echo $data_userData[0]['Organization'] ?>">
            </div>

            <div class="form-group">
                <label for="AccountStatus">AccountStatus</label>
                <input type="text" class="form-control" id="AccountStatus" value="<? echo $data_userData[0]['AccountStatus'] ?>"  disabled>
            </div>

            <div class="form-group">
                <label for="Password">Password</label>
                <input type="text" class="form-control" id="Password" value="">
            </div>

            <div class="form-group">
                <label for="RegisterDate">RegisterDate</label>
                <input type="text" class="form-control" id="RegisterDate" value="<? echo $data_userData[0]['RegisterDate'] ?>"  disabled>
            </div>

            <div class="form-group">
                <label for="SessionIpv4">SessionIpv4</label>
                <input type="text" class="form-control" id="SessionIpv4" value="<? echo $data_userData[0]['SessionIpv4'] ?>"  disabled>
            </div>


            <div class="form-group">
                <label for="IsAdmin">IsAdmin <strong>(1 is true, 0 is false)</strong></label>
                <input type="text" class="form-control" id="IsAdmin" value="<? echo $data_userData[0]['IsAdmin'] ?> ">
            </div>

                <p><button type="submit" class="btn btn-default">Submit</button></p>

            </form>

        </div>
    </div>
</div>