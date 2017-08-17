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
<?/* GLOBAL NAVIGATION BAR*/?>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <?/* MOBILE TOGGLES*/?>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="?Index"><img src="img/logo.png" alt="Access" height="50" width="auto"/></a>
            <a class="navbar-brand" href="?DecisionMaking"><img src="img/decisionsupport.png" alt="DecisionSupport" height="50" width="auto"/></a>
        </div>
        <?/* LEFT NAVIGATION BAR, SOME NAVIGATION POINTS ONLY VISIBLE FOR ADMINS*/?>
        <div id="navbar" class="navbar-collapse collapse">
            <?/* BEGIN ADMIN*/?>
            <? if ($data_isAdmin) { ?>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="?Admin" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Admin<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!--<li><a href="?Admin">Main</a></li>-->
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">ACCESS</li>
                        <li><a href="?AdminCategoriesOverview">Change Categories</a></li>
                        <li><a href="?AdminAuthenticationsOverview">Change Authentication Schemes</a></li>
                        <li><a href="?AdminAuthenticationFeatureOverview">Change Subfeatures of Authentication Schemes</a></li>
                        <li><a href="?AdminScaleValuesOverview">Change Scale Values</a></li>
                        <li><a href="?AdminClassifyAuthenticationsFeatureOverview">Classify Authentication Schemes</a></li>
                        <li><a href="?AdminPerformancesOverview">Set/Check Performances in Features</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">CMS ADMIN PAGES</li>
                        <li><a href="?AdminActiveThreads">Thread Overview</a></li>
                        <li><a href="?AdminUserOverview">Edit Users</a></li>
                        <li><a href="?AdminContentOverview">Edit Content</a></li>
                        <li><a href="?AdminFeatureOverview">Edit Features</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Logging</li>
                        <li><a href="?AdminLog">Decision Logging</a></li>
                    </ul>
                </li>
            </ul>
            <?};?>
            <?/* END ADMIN*/ ?>
            <?/* BEGIN FOR LOGGED IN USER*/?>
            <? if ($data_validSession) { ?>
                <ul class="nav navbar-nav navbar-right">
                <li class="dropdown" >
                    <a href="?User" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Hello <? echo $data_name ?> | User Menu<span class="caret"></span></a>
                    <ul class="dropdown-menu" >
                        <li><a href = "?User" >User Overview</a ></li>
                        <li role="separator" class="divider" ></li>
                        <li><a href="?Logout"> Logout</a ></li>
                    </ul >
                </li >
            </ul >
                <?/* END FOR LOGGED IN USER*/?>
           <? } else { ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="?Login">Login/Register</a></li>
                    </ul>
                    <?};?>
        </div>
    </div>
</nav>
<?/* END NAVIGATION*/?>