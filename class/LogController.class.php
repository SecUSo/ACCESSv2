<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2017   Christian Engelbert, Philip Stumpf
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
 * Class LogObject
 * @desc Class for an easier Data Handling of Log-Data
 * @var $quantity - Quantity of the JSON-String
 * @var $jsondata - The JSON-String of the Logged Priority-List
 */
class LogObject
{
    public $quantity;
    public $jsondata;
}

/**
 * Class LogController
 * @desc Controller for the AdminLog Page
 * @var $dbController - The Database-Controller
 */
class LogController
{
    private $dbController;

    /**
     * LogController constructor.
     */
    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * getTopRequests()
     * @desc Gets the Top-10 logged Priority-Lists from the Decision Making
     * @return array - Array of LogObjects
     */
    public function getFeatureLog()
    {
        $query = "SELECT
                    quantity,
                    jsondata
                  FROM
                    log_feature
                  ORDER BY quantity DESC
                 ";

        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach($result as $row){
            $logObj = new LogObject();
            $logObj->jsondata = $row['jsondata'];
            $logObj->quantity = $row['quantity'];
            $output[] = $logObj;
        }

        return $output;
    }

    /**
     * getTopRequests()
     * @desc Gets the Top-10 logged Priority-Lists from the Decision Making
     * @return array - Array of LogObjects
     */
    public function getSubfeatureAndLog()
    {
        $query = "SELECT
                    quantity,
                    jsondata
                  FROM
                    log_subfeatures_and
                  ORDER BY quantity DESC
                  LIMIT 10
                 ";

        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach($result as $row){
            $logObj = new LogObject();
            $logObj->jsondata = $row['jsondata'];
            $logObj->quantity = $row['quantity'];
            $output[] = $logObj;
        }

        return $output;
    }

    /**
     * getTopRequests()
     * @desc Gets the Top-10 logged Priority-Lists from the Decision Making
     * @return array - Array of LogObjects
     */
    public function getSubfeatureOrLog()
    {
        $query = "SELECT
                    quantity,
                    jsondata
                  FROM
                    log_subfeatures_or
                  ORDER BY quantity DESC
                  LIMIT 10
                 ";

        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach($result as $row){
            $logObj = new LogObject();
            $logObj->jsondata = $row['jsondata'];
            $logObj->quantity = $row['quantity'];
            $output[] = $logObj;
        }

        return $output;
    }

}
?>