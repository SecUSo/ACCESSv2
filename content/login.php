<?/* LINK TO JAVASCRIPT FILE*/?>
<script src="js/login.js"></script>

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
<?/* LOGIN AND REGISTRATION PAGE*/?>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <?/* REGISTRATION AREA */?>
            <h2 class="form-register-heading">Register</h2>
            <div id="reg-error1" class="reg-error alert alert-danger">
                <strong>Failure!</strong> Please check your data!
            </div>
            <div id="reg-error2" class="reg-error alert alert-danger">
                <strong>Failure!</strong> Wrong invite code!
            </div>
            <div id="reg-error3" class="reg-error alert alert-danger">
                <strong>Failure!</strong> Please check your data!
            </div>
            <form class="form-register">
                <label for="registerinputTitle" class="sr-only"></label>
                <select class="form-control" id="registerinputTitle" placeholder="Title">
                    <option></option>
                    <option>Dr.</option>
                    <option>Prof.</option>
                    <option>Prof. Dr.</option>
                </select>
                <label for="registerinputFirstName" class="sr-only"></label>
                <input type="name" id="registerinputFirstName" class="form-control" placeholder="First name" required>
                <label for="registerinputLastName" class="sr-only"></label>
                <input type="name" id="registerinputLastName" class="form-control" placeholder="Last name" required>
                <label for="registerinputOrganization" class="sr-only"></label>
                <input type="company" id="registerinputOrganization" class="form-control" placeholder="Organization">
                <label for="registerinputEMail" class="sr-only"></label>
                <input type="email" id="registerinputEMail" class="form-control" placeholder="Email address" required
                       autofocus>
                <label for="registerinputPassword" class="sr-only"></label>
                <input type="password" id="registerinputPassword" class="form-control" placeholder="Password" required>
                <label for="registerinputInviteCode" class="sr-only"></label>
                <input type="text" id="registerinputInviteCode" class="form-control" placeholder="Invite code" required>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me" required> I have read and do accept the Terms of
                        License
                    </label>
                </div>
                <p>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
                </p>
            </form>
        </div><!--/.col-sm6 -->
        <?/* LOGIN AREA*/?>
        <div class="col-sm-6">
            <h2 class="form-signin-heading">Sign in</h2>
            <div id="login-error1" class="login-error alert alert-danger">
                <strong>Failure!</strong> Please check your data!
            </div>
            <form class="form-signin">
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <p>&nbsp;</p>
                <p>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </p>
            </form>
        </div><!--/.col-sm6 -->
    </div> <!-- row -->
</div> <!-- /container -->

