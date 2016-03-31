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
 * Class AdminCategoriesOverview
 * @desc - The Backend-Page for an Admin Page that gives the Ability to View and Change Categories, Features and
 *          SubFeatures in the System.
 * @var $isViewable - If the class has viewable Content
 * @var $categoriesController - The Categories-Controller
 * @var $sessionController - The Session-Controller
 */
class AdminCategoriesOverview
{
    public static $isViewable = TRUE;
    private $categoriesController;
    private $sessionController;

    /**
     * AdminCategoriesOverview constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->categoriesController = new CategoriesController();
        $this->initTemplate();
    }

    /**
     * initTemplate()
     * @desc Gets all necessary Data from the Controllers and Builds up the Template.
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/categoriesoverview.php The Maincontent Templatefile
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - CONTENT";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->categoriesController->getIndexContent();
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/categoriesoverview.php");
        include_once("content/footer.php");
    }

}
?>
