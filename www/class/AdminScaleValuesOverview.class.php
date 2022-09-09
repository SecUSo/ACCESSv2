<?php
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
 * Class AdminScaleValuesOverview
 * @desc Class for a page that shows all Features of the System for the Admin. The Admin is able to select a Feature
 *          and will then see a AdminScaleValues-Page.
 * @var $isViewable : Static Boolean if the page is viewable
 * @var $categoriesController : Needed Controller for the Class
 * @var $sessionController : The Session-Controller
 */
class AdminScaleValuesOverview
{
    public static $isViewable = TRUE;
    private $categoriesController;
    private $sessionController;

    /**
     * AdminScaleValuesOverview constructor.
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
     * @desc Gets the necessary data from the Controllers for the Templates and loads the Templates
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/scalevaluesoverview.php The Maincontent Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - SCALE-VALUES";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->categoriesController->getScaleValuesContent();

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/scalevaluesoverview.php");
        include_once("content/footer.php");
    }

}

?>
