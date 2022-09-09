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
 * Class AdminAuthenticationFeature
 * @desc Will load an Admin-Page for the Admin to Change the SubFeatures of the GET-Selected Authentication Scheme
 * @var $isViewable : Static Boolean if the Page gives any Output
 * @var $authenticationFeatureController : Needed Controller Class
 * @var $sessionController : Session Controller Class
 */
class AdminAuthenticationFeature
{
    public static $isViewable = TRUE;
    private $authenticationFeatureController;
    private $sessionController;

    /**
     * AdminAuthenticationFeature constructor.
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
     * @desc Will initiate the Variables for the Templates and load them
     * @file content/header.php The Header pHTML File for the Template
     * @file content/navigation.php The Navigationbar for the Template
     * @file content/admin/authenticationfeature.php The Maincontent-Block for the Template
     * @file content/footer.php The Fooder pHTML File for the Template
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - CONTENT";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_authName = $this->getParams();
        $data_content = $this->authenticationFeatureController->getAuthContent($this->getParams());
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/authenticationfeature.php");
        include_once("content/footer.php");
    }

    /**
     * getParams()
     * @return string - The GET-Requested Authentication-Scheme if set, else an empty String
     * @desc Will return the Name of the GET-Requested Authentication Scheme
     */
    private function getParams()
    {
        if(isset($_GET['auth']))
            return $_GET['auth'];
        else
            return "";
    }

}
?>
