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
 * Class AdminPerformancesOverview
 * @desc Class for the Overview Page to show the Performances of a Feature. It shows a list of all available Features.
 * @var $isViewable - If the Page has viewable Content.
 * @var $classifyAuthenticationsController - The Controller the Class uses.
 * @var $sessionController - The Session Controller
 */
class AdminPerformancesOverview
{
    public static $isViewable = TRUE;
    private $classifyAuthenticationsController;
    private $sessionController;

    /**
     * AdminPerformancesOverview constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->initTemplate();
    }

    /**
     * initTemplate()
     * @desc Gets all necessary Data from the Controller for the Template Files and loads the Template Files.
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/adminperformancesoverview.php The Maincontent Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - PERFORMANCES OVERVIEW";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->classifyAuthenticationsController->getIndexContent();
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/adminperformancesoverview.php");
        include_once("content/footer.php");
    }

}
?>
