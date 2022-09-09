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
 * Class AdminSavePerformances
 * @desc Class to save the Performances of a Feature.
 * @var $isViewable - Determines if the Page has viewable Content
 * @var $sessionController - The Session Controller
 * @var $performancesController - The Controller used by the Class
 * @var $feature - The Feature the Performance values are related to
 * @var $performances - The Performance-Values of the Feature
 */
class AdminSavePerformances
{
    public static $isViewable = TRUE;
    private $sessionController;
    private $performancesController;
    private $feature;
    private $performances;

    /**
     * AdminSavePerformances constructor.
     */
    public function __construct()
    {
        $this->performancesController = new PerformancesController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->getParams();
        $this->performancesController->setPerformances($this->feature, $this->performances);
    }

    /**
     * getParams()
     * @desc Checks if the POST-Variables 'feature' and 'json' are set and if the User is an Admin.
     */
    private function getParams()
    {
        if($this->sessionController->getIsAdmin() != 0 && isset($_POST['feature']) && isset($_POST['json'])){
            $this->feature = htmlspecialchars($_POST['feature']);
            $this->performances = json_decode($_POST['json'], true);
        }else{
            die();
        }
    }

}

?>
