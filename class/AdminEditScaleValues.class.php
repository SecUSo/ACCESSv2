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
 * Class AdminEditScaleValues
 * @desc The Class gets Data in form of the Scale-Values in a selected Feature. The Data will be inserted into the
 *          Database by the CategoriesController.
 * @var $isViewable : Static Boolean if the page is viewable
 * @var $categoriesController : Needed Controller for the Class
 * @var $sessionController : The Session-Controller
 */
class AdminEditScaleValues
{
    public static $isViewable = TRUE;
    private $categoriesController;
    private $sessionController;

    /**
     * AdminEditScaleValues constructor.
     */
    public function __construct()
    {
        $this->categoriesController = new CategoriesController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
    }

    /**
     * getParams()
     * @desc Gets the POST-Data from the POST-Variables 'json' and 'feature' and lets the categoriesController modify
     *          the data in the Database
     * @data The POST-Variable 'json' and 'feature'
     *          Feature contains a String with the Feature the Data belongs to
     *          JSON contains a JSON-String with the following format:
     * {
     *  "Class-Name":{
     *                  "Class-Name":value,
     *                  "Class-Name":value,
     *                  ...
     *                },
     *  "Class-Name": ...
     * }
     */
    private function getParams(){
        // Decode the incoming Data from the Front-End
        if($this->sessionController->getIsAdmin() != 0 && isset($_POST['json']) && isset($_POST['feature'])) {
            $feature = htmlspecialchars($_POST['feature']);
            $json = json_decode($_POST['json'], true);
            $this->categoriesController->setScaleValues($feature, $json);
        }
    }

}
?>