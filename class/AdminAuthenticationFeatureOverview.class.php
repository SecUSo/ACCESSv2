<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Engelbert
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

/**
 * Class AdminAuthenticationFeatureOverview
 * @desc Class for the Overview of the Authentication Schemes so the Admin can select one to change its SubFeatures
 * @var $isViewable : Static Boolean if the Page is Viewable
 * @var $authenticationFeatureController : Needed Controller Class
 * @var $sessionController : SessionController-Class
 */
class AdminAuthenticationFeatureOverview
{
    public static $isViewable = TRUE;
    private $authenticationFeatureController;
    private $sessionController;

    /**
     * AdminAuthenticationFeatureOverview constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->authenticationFeatureController = new AuthenticationFeatureController();
        $this->initTemplate();
    }

    /**
     * initTemplate()
     * @desc Will get the needed Variables for the Templates and load the Templates
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/authenticationfeatureoverview.php The Maincontent Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - CONTENT";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->authenticationFeatureController->getIndexContent();
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/authenticationfeatureoverview.php");
        include_once("content/footer.php");
    }

}

?>
