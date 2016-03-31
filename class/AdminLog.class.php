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
 * Class AdminLog
 * @desc Gets the Top-10 logged Decision-Priority-Lists from the Database and shows them to a valid Administrator.
 * @var $sessionController - The Session-Controller
 * @var $logController - The Log-Controller used to get the Data from the Database
 */
class AdminLog
{
    private $sessionController;
    private $logController;

    /**
     * AdminLog constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        if(!$this->sessionController->getIsAdmin())
            die("error: no access");

        $this->logController = new LogController();
        $this->initTemplate();
    }

    /**
     * initTemplate()
     * @desc Gets the Data from the Database and builds up the Template
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/logoverview.php The Maincontent-Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - DECISION LOG";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data = $this->logController->getTopRequests();

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/logoverview.php");
        include_once("content/footer.php");
    }
    
}
?>