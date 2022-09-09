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
 * Class AdminScaleValues
 * @desc This Class is for the Admin-Page to change the Scale-Values of a Feature. The page shows a table with all
 *          Classes a Feature has and the Scale-Values of the Feature that are already known in the Database. The Admin
 *          can change the Scale-Values on this page.
 * @var $isViewable : Static Boolean if the page is viewable
 * @var $categoriesController : Needed Controller for the Class
 * @var $sessionController : The Session-Controller
 */
class AdminScaleValues
{
    public static $isViewable = TRUE;
    private $categoriesController;
    private $sessionController;

    /**
     * AdminScaleValues constructor.
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
     * @desc Gets the necessary Data from the Database for the Templates and loads the Templates
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/scalevalues.php The Maincontent Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $requestedFeature = $this->getParams();
        $data_pagetitle = "ACCESS BASIC - SCALE-VALUES";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->categoriesController->getScaleValuesContentForFeature($requestedFeature);
        $data_tableHeader = $this->getTableHeaders($data_content);
        
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/scalevalues.php");
        include_once("content/footer.php");
    }

    /**
     * getParams()
     * @return string The selected Feature from the GET-Parameter 'feature'
     */
    private function getParams()
    {
        if(isset($_GET['feature'])){
            return htmlspecialchars($_GET['feature']);
        }else{
            return "";
        }
    }

    /**
     * getTableHeaders($content)
     * @desc Gets an Array with all Class-Names and their corresponding Scale-Values for a Feature and returns an Array
     *          with all Class-Names. This Function is used for the Table-Headers in the Template.
     * @param $content - An array with the following format:
     *                      Array(
     *                              Class-Name => Array(
     *                                                  Class-Name => Scale-Value
     *                                                  Class-Name => Scale-Value
     *                                                  ...
     *                                                  )
     *                              Class-Name => Array(...)
     *                           )
     * @return array - An array that contains all Class-Names from the Input
     *  Array(
     *          [0] => Class-Name
     *          [1] => Class-Name
     *          ...
     *       )
     */
    private function getTableHeaders($content)
    {
        $output = array();
        foreach($content as $className => $cArr){
            $output[] = $className;
            foreach($cArr as $iClassName => $val){
                $output[] = $iClassName;
            }
            break;
        }
        return $output;
    }

}
?>
