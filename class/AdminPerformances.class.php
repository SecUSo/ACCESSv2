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
 * Class AdminPerformances
 * @desc Class used to show a Performances Page to an Admin which Shows the Scale-Values, Performances and the
 *          Consistency Index of the Performances.
 * @var $isViewable - If the Class has viewable Content
 * @var $performancesController - The Controller the Class uses
 * @var $sessionController - The Session Controller
 */
class AdminPerformances
{
    public static $isViewable = TRUE;
    private $performancesController;
    private $sessionController;

    /**
     * AdminPerformances constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->performancesController = new PerformancesController();
        $this->initTemplate();
    }

    /**
     * initTemplate()
     * @desc Gets all necessary Data from the Controller for the Templates and loads the Templates used for the Page.
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/adminperformances.php The Maincontent Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $data_param = $this->getParams();
        $data_pagetitle = "ACCESS BASIC - PERFORMANCES OVERVIEW";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_tableHeaders = $this->performancesController->getIndexHeaders();
        $data_content = $this->performancesController->getIndexContent($data_param);
        $data_performances = $this->performancesController->getPerformances($data_content);
        $data_consistency = $this->performancesController->getConsistency($data_content, $data_performances);

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/adminperformances.php");
        include_once("content/footer.php");
    }

    /**
     * getParams()
     * @desc Checks if the User is an Admin and if the GET-Variable 'feature' is set.
     * @return string - The Content of the GET-Variable 'feature' if its set else an empty String.
     */
    private function getParams()
    {
        if($this->sessionController->getIsAdmin() != 0 && isset($_GET['feature'])){
            return htmlspecialchars($_GET['feature']);
        }else{
            return "";
        }
    }

}
?>
